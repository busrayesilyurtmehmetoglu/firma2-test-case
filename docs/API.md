# API Dokümantasyonu

## Authentication

### Kayıt Ol (Register)
- **POST** `/auth/register`
- Body (JSON):
  ```json
  {
    "username": "testuser",
    "email": "test@example.com",
    "password": "password123"
  }
  ```
- Response: 201 Created / 409 Conflict

### Giriş Yap (Login)
- **POST** `/auth/login`
- Body (JSON):
  ```json
  {
    "email": "test@example.com",
    "password": "password123"
  }
  ```
- Response: 200 OK
  ```json
  { "token": "<JWT_TOKEN>" }
  ```

## Dış Servis Entegrasyonu (REST)

### Veri Çek (Örnek: jsonplaceholder)
- **POST** `/api/external/rest`
- Header: `Authorization: Bearer <JWT_TOKEN>`
- Body (JSON):
  ```json
  {
    "endpoint": "posts/1",
    "params": {}
  }
  ```
- Response: 200 OK (jsonplaceholder'dan dönen veri)

## Hata Yönetimi
- 401 Unauthorized: Token eksik/geçersiz
- 409 Conflict: Kayıtlı email
- 422 Validation Error: Eksik parametre
- 500 Server Error: Sunucu hatası

## Notlar
- Tüm /api/* endpointleri JWT ile korunur.
- Dış servis verisi önce Redis cache, yoksa MySQL'den, o da yoksa dış servisten çekilir.
- Başarılı/başarısız tüm entegrasyonlar loglanır. 