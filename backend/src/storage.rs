use std::path::Path;
use std::sync::Arc;

use serde_json::Value;
use thiserror::Error;
use tokio::fs;
use tokio::sync::Mutex;

use crate::models::{CreatePost, Post, UpdatePost};

#[derive(Debug, Error)]
pub enum StoreError {
    #[error("io error: {0}")]
    Io(#[from] std::io::Error),
    #[error("json error: {0}")]
    Json(#[from] serde_json::Error),
    #[error("not found: {0}")]
    NotFound(String),
}

#[derive(Clone)]
pub struct FileStore {
    path: Arc<String>,
    // serialize read/write operations to the file
    lock: Arc<Mutex<()>>,
}

impl FileStore {
    pub async fn new<P: Into<String>>(path: P) -> Result<Self, StoreError> {
        let path: String = path.into();
        if let Some(dir) = Path::new(&path).parent() {
            fs::create_dir_all(dir).await?;
        }
        if !Path::new(&path).exists() {
            fs::write(&path, b"[]").await?;
        }
        Ok(Self {
            path: Arc::new(path),
            lock: Arc::new(Mutex::new(())),
        })
    }

    pub async fn list(&self) -> Result<Vec<Post>, StoreError> {
        let _guard = self.lock.lock().await;
        let data = fs::read_to_string(&*self.path).await?;
        let posts: Vec<Post> = serde_json::from_str(&data)?;
        Ok(posts)
    }

    pub async fn get(&self, id: &str) -> Result<Post, StoreError> {
        let _guard = self.lock.lock().await;
        let data = fs::read_to_string(&*self.path).await?;
        let posts: Vec<Post> = serde_json::from_str(&data)?;
        posts
            .into_iter()
            .find(|p| p.id == id || p.slug == id)
            .ok_or_else(|| StoreError::NotFound(id.to_string()))
    }

    pub async fn create(&self, payload: CreatePost) -> Result<Post, StoreError> {
        let _guard = self.lock.lock().await;
        let data = fs::read_to_string(&*self.path).await?;
        let mut posts: Vec<Post> = serde_json::from_str(&data)?;
        let post = Post::new(payload);
        posts.push(post.clone());
        let pretty = to_pretty(&posts)?;
        fs::write(&*self.path, pretty).await?;
        Ok(post)
    }

    pub async fn update(&self, id: &str, payload: UpdatePost) -> Result<Post, StoreError> {
        let _guard = self.lock.lock().await;
        let data = fs::read_to_string(&*self.path).await?;
        let mut posts: Vec<Post> = serde_json::from_str(&data)?;
        let mut found = false;
        for p in &mut posts {
            if p.id == id || p.slug == id {
                p.apply_update(payload.clone());
                found = true;
                break;
            }
        }
        if !found {
            return Err(StoreError::NotFound(id.to_string()));
        }
        let pretty = to_pretty(&posts)?;
        fs::write(&*self.path, pretty).await?;
        // return the updated post
        let updated = posts
            .into_iter()
            .find(|p| p.id == id || p.slug == id)
            .expect("post must exist after update");
        Ok(updated)
    }

    pub async fn delete(&self, id: &str) -> Result<(), StoreError> {
        let _guard = self.lock.lock().await;
        let data = fs::read_to_string(&*self.path).await?;
        let mut posts: Vec<Post> = serde_json::from_str(&data)?;
        let orig_len = posts.len();
        posts.retain(|p| !(p.id == id || p.slug == id));
        if posts.len() == orig_len {
            return Err(StoreError::NotFound(id.to_string()));
        }
        let pretty = to_pretty(&posts)?;
        fs::write(&*self.path, pretty).await?;
        Ok(())
    }
}

fn to_pretty<T: serde::Serialize>(val: &T) -> Result<Vec<u8>, StoreError> {
    let value: Value = serde_json::to_value(val)?;
    let bytes = serde_json::to_vec_pretty(&value)?;
    Ok(bytes)
}
