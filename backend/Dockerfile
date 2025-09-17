# --- Builder stage ---
FROM rust:1 as builder
WORKDIR /app

# Cache deps
COPY Cargo.toml ./
# Create an empty src to allow dependency resolution
RUN mkdir -p src && echo "fn main() {}" > src/main.rs
RUN cargo build --release || true

# Now copy actual sources
COPY src ./src

# Build release
RUN cargo build --release

# --- Runtime stage ---
FROM debian:bookworm-slim as runtime

# Create non-root user (optional but recommended)
RUN useradd -m -u 10001 appuser

# Workdir
WORKDIR /app

# Copy binary from builder
COPY --from=builder /app/target/release/blog-backend /usr/local/bin/app

# Data directory will be mounted via volume (compose)
# EXPOSE port used by the app
EXPOSE 8080

ENV HOST=0.0.0.0
ENV PORT=8080

USER appuser

CMD ["/usr/local/bin/app"]
