use std::{sync::Arc};

use axum::{
    extract::{Path, State},
    http::StatusCode,
    response::IntoResponse,
    routing::{delete, get, post, put},
    Json, Router,
};
use axum::{http::Request, response::Response, middleware::{self, Next}};
use tower_http::{cors::{Any, CorsLayer}, trace::TraceLayer};
use tracing::{info, Level};
use tracing_subscriber::EnvFilter;
use tokio::net::TcpListener;

mod models;
mod storage;
mod users;
mod storage_users;

use crate::models::{CreatePost, Post, UpdatePost};
use crate::storage::FileStore;
use crate::users::{CreateUser, LoginRequest, UpdateUser, User};
use crate::storage_users::UserFileStore;
use chrono::{Utc, Duration};
use serde::{Deserialize, Serialize};
use jsonwebtoken::{encode, Header, EncodingKey};

#[derive(Clone)]
struct AppState {
    store: Arc<FileStore>,
    user_store: Arc<UserFileStore>,
    jwt_secret: Arc<String>,
}

#[tokio::main]
async fn main() -> anyhow::Result<()> {
    // init tracing
    tracing_subscriber::fmt()
        .with_max_level(Level::INFO)
        .with_env_filter(EnvFilter::from_default_env())
        .init();

    // init storage
    let store = Arc::new(FileStore::new("data/posts.json").await?);
    let user_store = Arc::new(UserFileStore::new("data/users.json").await?);

    // Seed default admin user if none exists
    if user_store.list().await?.is_empty() {
        let pwd = std::env::var("ADMIN_PASSWORD").unwrap_or_else(|_| "admin123".to_string());
        let _ = user_store.create(CreateUser {
            username: "admin".to_string(),
            password: pwd,
            roles: vec!["admin".to_string()],
        }).await;
        info!("Seeded default admin user: username=admin");
    }

    // build app
    let cors = CorsLayer::new()
        .allow_origin(Any)
        .allow_methods(Any)
        .allow_headers(Any);

    let jwt_secret = Arc::new(std::env::var("JWT_SECRET").unwrap_or_else(|_| "dev-secret-change-me".to_string()));
    let app_state = AppState { store, user_store, jwt_secret };

    let public = Router::new()
        .route("/health", get(health))
        .route("/posts", get(list_posts))
        .route("/posts/:id", get(get_post))
        .route("/users", get(list_users))
        .route("/users/:id", get(get_user))
        .route("/auth/login", post(login));

    let protected = Router::new()
        .route("/posts", post(create_post))
        .route("/posts/:id", put(update_post).delete(delete_post))
        .route("/users", post(create_user))
        .route("/users/:id", put(update_user).delete(delete_user))
        .route_layer(middleware::from_fn(require_auth));

    let app = public
        .merge(protected)
        .with_state(app_state)
        .layer(TraceLayer::new_for_http())
        .layer(cors);

    // bind address using TcpListener (Axum 0.7)
    let host = std::env::var("HOST").unwrap_or_else(|_| "0.0.0.0".to_string());
    let port: u16 = std::env::var("PORT").ok().and_then(|p| p.parse().ok()).unwrap_or(8080);
    let listener = TcpListener::bind(format!("{}:{}", host, port)).await?;
    let addr = listener.local_addr()?;
    info!("Starting server on {}", addr);
    axum::serve(listener, app.into_make_service()).await?;

    Ok(())
}

async fn health() -> impl IntoResponse {
    (StatusCode::OK, "ok")
}

async fn list_posts(State(state): State<AppState>) -> Result<Json<Vec<Post>>, (StatusCode, String)> {
    let posts = state.store.list().await.map_err(internal_err)?;
    Ok(Json(posts))
}

async fn get_post(
    State(state): State<AppState>,
    Path(id): Path<String>,
) -> Result<Json<Post>, (StatusCode, String)> {
    let post = state.store.get(&id).await.map_err(map_store_err)?;
    Ok(Json(post))
}

async fn create_post(
    State(state): State<AppState>,
    Json(payload): Json<CreatePost>,
) -> Result<(StatusCode, Json<Post>), (StatusCode, String)> {
    let post = state.store.create(payload).await.map_err(internal_err)?;
    Ok((StatusCode::CREATED, Json(post)))
}

async fn update_post(
    State(state): State<AppState>,
    Path(id): Path<String>,
    Json(payload): Json<UpdatePost>,
) -> Result<Json<Post>, (StatusCode, String)> {
    let post = state.store.update(&id, payload).await.map_err(map_store_err)?;
    Ok(Json(post))
}

async fn delete_post(
    State(state): State<AppState>,
    Path(id): Path<String>,
) -> Result<StatusCode, (StatusCode, String)> {
    state.store.delete(&id).await.map_err(map_store_err)?;
    Ok(StatusCode::NO_CONTENT)
}

fn internal_err<E: std::fmt::Display>(err: E) -> (StatusCode, String) {
    (StatusCode::INTERNAL_SERVER_ERROR, err.to_string())
}

fn map_store_err(err: storage::StoreError) -> (StatusCode, String) {
    match err {
        storage::StoreError::NotFound(id) => (StatusCode::NOT_FOUND, format!("Post {} not found", id)),
        other => internal_err(other),
    }
}

// ===== JWT Middleware =====
use jsonwebtoken::{DecodingKey, Validation, decode};

async fn require_auth<B>(State(state): State<AppState>, req: Request<B>, next: Next<B>) -> Result<Response, StatusCode> {
    let unauthorized = Err(StatusCode::UNAUTHORIZED);
    let auth = if let Some(h) = req.headers().get(axum::http::header::AUTHORIZATION) {
        h.to_str().ok()
    } else { None };
    let Some(auth) = auth else { return unauthorized; };
    let token = auth.strip_prefix("Bearer ").unwrap_or("");
    if token.is_empty() { return unauthorized; }
    let key = DecodingKey::from_secret(state.jwt_secret.as_bytes());
    if decode::<Claims>(token, &key, &Validation::default()).is_err() {
        return unauthorized;
    }
    Ok(next.run(req).await)
}

// ===== Users Handlers =====
async fn list_users(State(state): State<AppState>) -> Result<Json<Vec<User>>, (StatusCode, String)> {
    let users = state.user_store.list().await.map_err(internal_err)?;
    // hide password_hash in response by serializing User (field is skip_serializing)
    Ok(Json(users))
}

async fn get_user(State(state): State<AppState>, Path(id): Path<String>) -> Result<Json<User>, (StatusCode, String)> {
    let user = state.user_store.get(&id).await.map_err(map_user_err)?;
    Ok(Json(user))
}

async fn create_user(State(state): State<AppState>, Json(input): Json<CreateUser>) -> Result<(StatusCode, Json<User>), (StatusCode, String)> {
    let user = state.user_store.create(input).await.map_err(internal_err)?;
    Ok((StatusCode::CREATED, Json(user)))
}

async fn update_user(State(state): State<AppState>, Path(id): Path<String>, Json(input): Json<UpdateUser>) -> Result<Json<User>, (StatusCode, String)> {
    let user = state.user_store.update(&id, input).await.map_err(map_user_err)?;
    Ok(Json(user))
}

async fn delete_user(State(state): State<AppState>, Path(id): Path<String>) -> Result<StatusCode, (StatusCode, String)> {
    state.user_store.delete(&id).await.map_err(map_user_err)?;
    Ok(StatusCode::NO_CONTENT)
}

#[derive(Serialize, Deserialize)]
struct Claims {
    sub: String,
    exp: usize,
}

async fn login(State(state): State<AppState>, Json(payload): Json<LoginRequest>) -> Result<Json<serde_json::Value>, (StatusCode, String)> {
    // find user by username
    let user = state.user_store.get(&payload.username).await.map_err(|_| (StatusCode::UNAUTHORIZED, "invalid credentials".to_string()))?;
    if !user.verify(&payload.password) {
        return Err((StatusCode::UNAUTHORIZED, "invalid credentials".into()));
    }
    let secret = &state.jwt_secret;
    let exp = (Utc::now() + Duration::hours(12)).timestamp() as usize;
    let claims = Claims { sub: user.id.clone(), exp };
    let token = encode(&Header::default(), &claims, &EncodingKey::from_secret(secret.as_bytes()))
        .map_err(internal_err)?;
    Ok(Json(serde_json::json!({ "token": token, "user": {"id": user.id, "username": user.username, "roles": user.roles} })))
}

fn map_user_err(err: storage_users::UserStoreError) -> (StatusCode, String) {
    match err {
        storage_users::UserStoreError::NotFound(id) => (StatusCode::NOT_FOUND, format!("User {} not found", id)),
        other => internal_err(other),
    }
}
