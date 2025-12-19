package main

import (
    "context"
    "fmt"
    "io/ioutil"
    "net/http"
    "os"
    "strconv"
//  "strings"
    "time"

    "github.com/go-redis/redis/v8"
)

var ctx = context.Background()
var redisClient *redis.Client

func init() {
    redisAddr := os.Getenv("REDIS_ADDR")
    if redisAddr == "" {
       redisAddr = "host.docker.internal:6379"
    }
    fmt.Println("[RedisCachePlugin] Connecting to Redis at:", redisAddr)

    redisClient = redis.NewClient(&redis.Options{
       Addr: redisAddr,
    })

    if err := redisClient.Ping(ctx).Err(); err != nil {
       fmt.Println("[RedisCachePlugin] Redis connection failed:", err)
    } else {
       fmt.Println("[RedisCachePlugin] Redis connected successfully.")
    }
}

// 🔥 Required symbol
var ClientRegisterer = registerer("redis-cache")

type registerer string

func (r registerer) RegisterClients(f func(string, func(context.Context, map[string]interface{}) (http.Handler, error))) {
    fmt.Println("[RedisCachePlugin] Registering HTTP client with name:", string(r))
    f(string(r), r.newRedisCacheHandler)
}

func (r registerer) newRedisCacheHandler(_ context.Context, cfg map[string]interface{}) (http.Handler, error) {
    serviceName := ""
//  tableName := ""
//  keyType := ""
    params := []string{}
    // Config parsing
    serviceName = os.Getenv("APP_SERVICE")
    if serviceName == "" {
        serviceName = "ms-service"
    }

//  if v, ok := cfg["table_name"].(string); ok {
//     tableName = v
//  }
//  if v, ok := cfg["key_type"].(string); ok {
//     keyType = v
//  }
    if v, ok := cfg["params"].([]interface{}); ok {
       for _, p := range v {
          if paramStr, ok := p.(string); ok {
             params = append(params, paramStr)
          }
       }
    }

    // Redis Key generator function
    keyGen := func(req *http.Request) string {
        path := req.URL.Path
        query := req.URL.RawQuery

        if query != "" {
            return fmt.Sprintf("krakend:%s.%s?%s", serviceName, path, query)
        }
        return fmt.Sprintf("krakend:%s.%s", serviceName, path)
//      if tableName == "" || keyType == "" {                        //Undefined config case
//             return fmt.Sprintf("krakend:%s:%s", serviceName, req.URL.Path)
//         }

//     var parts []string
//     if serviceName != "" {
//        parts = append(parts, serviceName)
//     }
//     if tableName != "" {
//        parts = append(parts,"tbl",tableName)
//     }
//     if keyType != "" {
//        parts = append(parts, keyType)
//     }
//
//     var paramValues []string
//     switch keyType {
//     case "paginate":
//        query := req.URL.Query()
//             page := query.Get("page")
//             limit := query.Get("limit")
//
//             if limit == "" {
//                 limit = "15"
//             }
//             if page == "" {
//                 page = "1"
//             }
//             // Final format: limit.page (e.g., 15.1 or 15.6)
//             paramValues = append(paramValues, fmt.Sprintf("%s.%s", limit, page))
//
//     case "single":
//             pathParts := strings.Split(strings.Trim(req.URL.Path, "/"), "/")
//             if len(pathParts) >= len(params) {
//                 start := len(pathParts) - len(params)
//                 for i := 0; i < len(params); i++ {
//                     val := pathParts[start+i]
//                     paramValues = append(paramValues, val)
//                 }
//             }
//         case "filter":
//             pathParts := strings.Split(strings.Trim(req.URL.Path, "/"), "/")
//                 if len(pathParts) > 0 {
//                     lastSegment := pathParts[len(pathParts)-1]
//                     paramValues = append(paramValues, lastSegment)
//                 }
//     default:
//        // Do nothing
//     }

//     if len(paramValues) > 0 {
//        parts = append(parts, strings.Join(paramValues, "_"))
//     }
//
//     return "krakend:" + strings.Join(parts, ".")
    }

    // Handler logic
    return http.HandlerFunc(func(w http.ResponseWriter, req *http.Request) {
       cacheKey := keyGen(req)
       fmt.Println("[RedisCachePlugin] Cache key generated:", cacheKey)

       // Try to Read Redis
       cached, err := redisClient.Get(ctx, cacheKey).Bytes()
       if err == nil {
          fmt.Println("[RedisCachePlugin] Cache hit")
          w.WriteHeader(http.StatusOK)
          w.Header().Set("Content-Type", "application/json")
          w.Write(cached)
          return
       }

       // Cache miss: call backend
       resp, err := http.DefaultClient.Do(req)
       if err != nil {
          http.Error(w, err.Error(), http.StatusInternalServerError)
          return
       }
       defer resp.Body.Close()
        fmt.Println("[RedisCachePlugin] Cache miss")

       bodyBytes, err := ioutil.ReadAll(resp.Body)
       if err != nil {
          http.Error(w, err.Error(), http.StatusInternalServerError)
          return
       }

       // Forward to client
       w.WriteHeader(resp.StatusCode)
       for name, values := range resp.Header {
          for _, value := range values {
             w.Header().Add(name, value)
          }
       }
       w.Write(bodyBytes)

       // Write to Redis
       if resp.StatusCode == http.StatusOK {
               ttlSeconds := 300 // default cache ttl
                if ttlStr := os.Getenv("REDIS_TTL"); ttlStr != "" {
                    if parsed, err := strconv.Atoi(ttlStr); err == nil {
                        ttlSeconds = parsed
                    } else {
                        fmt.Println("[RedisCachePlugin] Invalid, using default 300 seconds")
                    }
                }
                ttl := time.Duration(ttlSeconds) * time.Second
                err := redisClient.Set(ctx, cacheKey, bodyBytes, ttl).Err()
                if err != nil {
                    fmt.Println("[RedisCachePlugin] Failed to store in Redis:", err)
                } else {
                    fmt.Println("[RedisCachePlugin] Stored response in Redis for key:", cacheKey, "with TTL:", ttl)
                }
       }
    }), nil
}

func main() {}