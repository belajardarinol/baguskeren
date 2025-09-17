# Blog Backend (Rust + Axum)

RESTful API sederhana untuk blog, menggunakan Rust, Axum, dan penyimpanan berbasis berkas JSON.

## Fitur
- CRUD Post: `GET /posts`, `GET /posts/:id|slug`, `POST /posts`, `PUT /posts/:id|slug`, `DELETE /posts/:id|slug`
- CORS terbuka untuk memudahkan integrasi dengan frontend statis
- Logging via `tracing`

## Menjalankan
Pastikan Rust terpasang. Kemudian:

```bash
cd backend
cargo run
```

Variabel lingkungan opsional:
- `HOST` (default `0.0.0.0`)
- `PORT` (default `8080`)

## Model
`Post`:
```json
{
  "id": "uuid",
  "title": "string",
  "slug": "string",
  "content": "string",
  "tags": ["string"],
  "created_at": "RFC3339",
  "updated_at": "RFC3339|null"
}
```

## Endpoints
- `GET /health` -> `ok`
- `GET /posts` -> daftar `Post[]`
- `GET /posts/:id` -> `Post` (menerima `id` atau `slug`)
- `POST /posts` -> buat post baru
  - body:
  ```json
  {
    "title": "...",
    "slug": "optional, jika tidak diisi akan otomatis dari title",
    "content": "...",
    "tags": ["..."]
  }
  ```
- `PUT /posts/:id` -> update post
  - body:
  ```json
  {
    "title": "optional",
    "slug": "optional",
    "content": "optional",
    "tags": ["optional"]
  }
  ```
- `DELETE /posts/:id` -> hapus post

## Catatan
- Data disimpan di `data/posts.json`. Untuk produksi, pertimbangkan menggunakan database (mis. SQLite/PostgreSQL) dan migrasi skema.
