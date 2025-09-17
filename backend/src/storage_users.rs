use std::path::Path;
use std::sync::Arc;

use thiserror::Error;
use tokio::fs;
use tokio::sync::Mutex;

use crate::users::{CreateUser, UpdateUser, User};

#[derive(Debug, Error)]
pub enum UserStoreError {
    #[error("io error: {0}")]
    Io(#[from] std::io::Error),
    #[error("json error: {0}")]
    Json(#[from] serde_json::Error),
    #[error("not found: {0}")]
    NotFound(String),
    #[error(transparent)]
    Other(#[from] anyhow::Error),
}

#[derive(Clone)]
pub struct UserFileStore {
    path: Arc<String>,
    lock: Arc<Mutex<()>>,
}

impl UserFileStore {
    pub async fn new<P: Into<String>>(path: P) -> Result<Self, UserStoreError> {
        let path: String = path.into();
        if let Some(dir) = Path::new(&path).parent() {
            fs::create_dir_all(dir).await?;
        }
        if !Path::new(&path).exists() {
            fs::write(&path, b"[]").await?;
        }
        Ok(Self { path: Arc::new(path), lock: Arc::new(Mutex::new(())) })
    }

    pub async fn list(&self) -> Result<Vec<User>, UserStoreError> {
        let _g = self.lock.lock().await;
        let s = fs::read_to_string(&*self.path).await?;
        let users: Vec<User> = serde_json::from_str(&s)?;
        Ok(users)
    }

    pub async fn get(&self, id_or_username: &str) -> Result<User, UserStoreError> {
        let _g = self.lock.lock().await;
        let s = fs::read_to_string(&*self.path).await?;
        let users: Vec<User> = serde_json::from_str(&s)?;
        users.into_iter().find(|u| u.id == id_or_username || u.username == id_or_username)
            .ok_or_else(|| UserStoreError::NotFound(id_or_username.to_string()))
    }

    pub async fn create(&self, input: CreateUser) -> Result<User, UserStoreError> {
        let _g = self.lock.lock().await;
        let s = fs::read_to_string(&*self.path).await?;
        let mut users: Vec<User> = serde_json::from_str(&s)?;
        // disallow duplicate username
        if users.iter().any(|u| u.username == input.username) {
            return Err(UserStoreError::Other(anyhow::anyhow!("username already exists")));
        }
        let user = User::new(input).map_err(UserStoreError::Other)?;
        users.push(user.clone());
        fs::write(&*self.path, serde_json::to_vec_pretty(&users)?).await?;
        Ok(user)
    }

    pub async fn update(&self, id_or_username: &str, input: UpdateUser) -> Result<User, UserStoreError> {
        let _g = self.lock.lock().await;
        let s = fs::read_to_string(&*self.path).await?;
        let mut users: Vec<User> = serde_json::from_str(&s)?;
        let mut found_idx: Option<usize> = None;
        for (i, u) in users.iter().enumerate() {
            if u.id == id_or_username || u.username == id_or_username { found_idx = Some(i); break; }
        }
        let idx = found_idx.ok_or_else(|| UserStoreError::NotFound(id_or_username.to_string()))?;
        let mut u = users.remove(idx);
        u.apply_update(input).map_err(UserStoreError::Other)?;
        users.insert(idx, u.clone());
        fs::write(&*self.path, serde_json::to_vec_pretty(&users)?).await?;
        Ok(u)
    }

    pub async fn delete(&self, id_or_username: &str) -> Result<(), UserStoreError> {
        let _g = self.lock.lock().await;
        let s = fs::read_to_string(&*self.path).await?;
        let mut users: Vec<User> = serde_json::from_str(&s)?;
        let before = users.len();
        users.retain(|u| !(u.id == id_or_username || u.username == id_or_username));
        if users.len() == before {
            return Err(UserStoreError::NotFound(id_or_username.to_string()));
        }
        fs::write(&*self.path, serde_json::to_vec_pretty(&users)?).await?;
        Ok(())
    }
}
