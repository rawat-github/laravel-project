# ------------------------
# Stage 1: Build the plugin
# ------------------------
FROM golang:1.23.7 AS builder

WORKDIR /app

# Copy go.mod and go.sum first to leverage Docker's caching
COPY go.mod go.sum ./

# Download dependencies
RUN go mod download

# Clean the module cache to ensure we're using the downloaded version
RUN go clean -modcache
# Copy the plugin code
COPY redis_cache_plugin.go .

# Build the plugin as a .so
RUN go build -buildmode=plugin -o redis_cache_plugin.so redis_cache_plugin.go

# ------------------------
# Stage 2: KrakenD image
# ------------------------
FROM devopsfaith/krakend:2.9.3

WORKDIR /etc/krakend

# Create plugins directory and copy the compiled plugin
RUN mkdir -p ./plugins
COPY --from=builder /app/redis_cache_plugin.so ./plugins/redis_cache_plugin.so

# Copy your krakend.json
COPY krakend.json .