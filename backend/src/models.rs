use chrono::{DateTime, Utc};
use serde::{Deserialize, Serialize};
use uuid::Uuid;

#[derive(Debug, Clone, Serialize, Deserialize)]
pub struct Post {
    pub id: String,
    pub title: String,
    pub slug: String,
    pub content: String,
    pub tags: Vec<String>,
    pub created_at: DateTime<Utc>,
    pub updated_at: Option<DateTime<Utc>>,
}

#[derive(Debug, Clone, Deserialize)]
pub struct CreatePost {
    pub title: String,
    pub slug: Option<String>,
    pub content: String,
    #[serde(default)]
    pub tags: Vec<String>,
}

#[derive(Debug, Clone, Deserialize)]
pub struct UpdatePost {
    pub title: Option<String>,
    pub slug: Option<String>,
    pub content: Option<String>,
    pub tags: Option<Vec<String>>,
}

impl Post {
    pub fn new(input: CreatePost) -> Self {
        let now = Utc::now();
        let id = Uuid::new_v4().to_string();
        let slug = input
            .slug
            .unwrap_or_else(|| slugify(&input.title));
        Self {
            id,
            title: input.title,
            slug,
            content: input.content,
            tags: input.tags,
            created_at: now,
            updated_at: None,
        }
    }

    pub fn apply_update(&mut self, upd: UpdatePost) {
        if let Some(title) = upd.title {
            self.title = title;
        }
        if let Some(slug) = upd.slug {
            self.slug = slug;
        }
        if let Some(content) = upd.content {
            self.content = content;
        }
        if let Some(tags) = upd.tags {
            self.tags = tags;
        }
        self.updated_at = Some(Utc::now());
    }
}

fn slugify(s: &str) -> String {
    let mut out = String::with_capacity(s.len());
    for ch in s.chars() {
        let c = ch.to_ascii_lowercase();
        if c.is_ascii_alphanumeric() {
            out.push(c);
        } else if c.is_ascii_whitespace() || "-_".contains(c) {
            out.push('-');
        }
    }
    // collapse consecutive '-'
    let mut collapsed = String::with_capacity(out.len());
    let mut last_dash = false;
    for c in out.chars() {
        if c == '-' {
            if !last_dash {
                collapsed.push('-');
                last_dash = true;
            }
        } else {
            collapsed.push(c);
            last_dash = false;
        }
    }
    collapsed.trim_matches('-').to_string()
}
