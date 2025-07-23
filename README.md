# Cisoft Microservice API

Kurumsal, sürdürülebilir, production-ready bir mikroservis API örneği.

## Özellikler
- REST ve SOAP dış servis entegrasyonu (örnek: jsonplaceholder)
- Katmanlı, esnek, OOP mimari (ServiceManager, Interface, Adapter Pattern)
- 30 dakika Redis cache, MySQL kalıcı kayıt
- Başarılı/başarısız tüm işlemler için loglama (integration_logs)
- JWT ile kimlik doğrulama ve yetkilendirme
- Docker ile kolay kurulum (PHP, MySQL, Redis, Nginx)
- Geliştirici dostu, kolay genişletilebilir yapı
- API dokümantasyonu ve Postman koleksiyonu

## Kurulum

### 1. Klonla
```sh
git clone <repo-url> && cd cisoft
```

### 2. Ortamı Başlat
```sh
docker-compose up -d --build
```

### 3. Composer Bağımlılıkları (ilk kurulumda gerekirse)
```sh
docker-compose exec app composer install
```

### 4. Migrationları Çalıştır
```sh
docker-compose exec app php spark migrate
```

### 5. .env Ayarları
- `.env` dosyasını düzenleyin (örnek: JWT_SECRET, DB ayarları)
- Örnek JWT secret:
  ```env
  JWT_SECRET=supersecretkey
  ```

## Kullanım

### 1. Kayıt Ol
```http
POST /auth/register
Content-Type: application/json
{
  "username": "testuser",
  "email": "test@example.com",
  "password": "password123"
}
```

### 2. Giriş Yap (Login)
```http
POST /auth/login
Content-Type: application/json
{
  "email": "test@example.com",
  "password": "password123"
}
```
- Dönen JWT token ile korumalı endpointlere erişebilirsiniz.

### 3. Dış Servisten Veri Çek (Örnek)
```http
POST /api/external/rest
Authorization: Bearer <JWT_TOKEN>
Content-Type: application/json
{
  "endpoint": "posts/1",
  "params": {}
}
```

## Test ve Dokümantasyon
- [docs/API.md](docs/API.md): Tüm endpointler ve örnekler
- `postman_collection.json`: Postman ile kolay test

## Mimari Notlar
- Tüm dış servisler için interface ve servis sınıfları ile esnek yapı
- ServiceManager ile cache, db, log, hata yönetimi merkezi
- Kolayca yeni servis/endpoint eklenebilir
- Tüm /api/* endpointleri JWT ile korunur

## Geliştirici Notları
- Kodlar PSR standartlarına ve SOLID prensiplerine uygun yazılmıştır
- Docker ortamı ile hızlı kurulum ve geliştirme
- Gelişmiş hata yönetimi ve loglama

## Katkı ve Lisans
- PR ve issue açabilirsiniz
- MIT Lisansı
