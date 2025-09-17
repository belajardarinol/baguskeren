use chrono::{DateTime, Utc};
use serde::{Deserialize, Serialize};
use uuid::Uuid;

use argon2::{password_hash::{PasswordHash, PasswordHasher, PasswordVerifier, SaltString}, Argon2};
use rand::thread_rng;

#[derive(Debug, Clone, Serialize, Deserialize)]
pub struct User {
    pub id: String,
    pub username: String,
    #[serde(skip_serializing)]
    pub password_hash: String,
    #[serde(default)]
    pub roles: Vec<String>,
    pub created_at: DateTime<Utc>,
    pub updated_at: Option<DateTime<Utc>>,
}

#[derive(Debug, Clone, Deserialize)]
pub struct CreateUser {
    pub username: String,
    pub password: String,
    #[serde(default)]
    pub roles: Vec<String>,
}

#[derive(Debug, Clone, Deserialize)]
pub struct UpdateUser {
    pub username: Option<String>,
    pub password: Option<String>,
    pub roles: Option<Vec<String>>,
}

#[derive(Debug, Clone, Deserialize)]
pub struct LoginRequest {
    pub username: String,
    pub password: String,
}

impl User {
    pub fn new(input: CreateUser) -> anyhow::Result<Self> {
        let now = Utc::now();
        let id = Uuid::new_v4().to_string();
        let password_hash = hash_password(&input.password)?;
        Ok(Self {
            id,
            username: input.username,
            password_hash,
            roles: input.roles,
            created_at: now,
            updated_at: None,
        })
    }

    pub fn apply_update(&mut self, upd: UpdateUser) -> anyhow::Result<()> {
        if let Some(username) = upd.username {
            self.username = username;
        }
        if let Some(password) = upd.password {
            self.password_hash = hash_password(&password)?;
        }
        if let Some(roles) = upd.roles {
            self.roles = roles;
        }
        self.updated_at = Some(Utc::now());
        Ok(())
    }

    pub fn verify(&self, password: &str) -> bool {
        verify_password(password, &self.password_hash).unwrap_or(false)
    }
}

pub fn hash_password(password: &str) -> anyhow::Result<String> {
    let salt = SaltString::generate(&mut thread_rng());
    let argon2 = Argon2::default();
    let hash = argon2.hash_password(password.as_bytes(), &salt)?;
    Ok(hash.to_string())
}

pub fn verify_password(password: &str, hash: &str) -> anyhow::Result<bool> {
    let parsed = PasswordHash::new(hash)?;
    Ok(Argon2::default()
        .verify_password(password.as_bytes(), &parsed)
        .is_ok())
}
