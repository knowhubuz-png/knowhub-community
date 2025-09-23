# KnowHub Community - Local Development Setup Guide

## 🚀 Quick Start

Bu sizning KnowHub Community loyihangizni localda ishga tushirish uchun to'liq qo'llanma.

## 📋 Talablar

- PHP 8.2+
- MySQL 8.0+ yoki MariaDB 10.3+
- Node.js 18+
- Composer
- Git

## 🔧 Backend Setup (Laravel)

### 1. Database Setup

```bash
# MySQL da database yaratish
mysql -u root -p
CREATE DATABASE knowhub_forum;
CREATE USER 'knowhub_forum'@'localhost' IDENTIFIED BY 'saman12';
GRANT ALL PRIVILEGES ON knowhub_forum.* TO 'knowhub_forum'@'localhost';
FLUSH PRIVILEGES;
EXIT;
```

### 2. Environment Configuration

`.env` fayli allaqachon sozlangan, lekin tekshirib oling:

```env
APP_NAME="KnowHub Community"
APP_ENV=local
APP_KEY=base64:IsXGE7Zo5tqudEFaRIhfEyYvnl9qraGYf2sRKXQOH+U=
APP_DEBUG=true
APP_URL=http://localhost:8000

# === Database ===
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=knowhub_forum
DB_USERNAME=knowhub_forum
DB_PASSWORD=saman12

# === Cache / Session / Queue ===
CACHE_DRIVER=file
SESSION_DRIVER=file
QUEUE_CONNECTION=database

# === Mail ===
MAIL_MAILER=smtp
MAIL_HOST=your_smtp_host
MAIL_PORT=587
MAIL_USERNAME=your_email
MAIL_PASSWORD=your_password
MAIL_ENCRYPTION=tls

# === Sanctum / CORS ===
SESSION_DOMAIN=localhost
SANCTUM_STATEFUL_DOMAINS=localhost:3000
```

### 3. Composer Dependencies

```bash
# Backend papkasiga o'ting
cd /path/to/knowhub-community

# Dependencies ni o'rnatish
composer install

# Key generatsiya qilish (agar kerak bo'lsa)
php artisan key:generate
```

### 4. Database Migrations

```bash
# Migrations ni ishga tushirish
php artisan migrate

# Seed data ni yuklash (agar kerak bo'lsa)
php artisan db:seed
```

### 5. Storage Link

```bash
# Storage papkasini public ga link qilish
php artisan storage:link
```

### 6. Backend Server

```bash
# Laravel development server ni ishga tushirish
php artisan serve
```

Backend: http://localhost:8000

## 🎨 Frontend Setup (Next.js)

### 1. Environment Configuration

`frontend/.env.local` fayli allaqachon yaratilgan:

```env
NEXT_PUBLIC_API_URL=http://localhost:8000/api/v1
NEXT_PUBLIC_APP_URL=http://localhost:3000
```

### 2. Node Dependencies

```bash
# Frontend papkasiga o'ting
cd frontend

# Dependencies ni o'rnatish
npm install
```

### 3. Development Server

```bash
# Next.js development server ni ishga tushirish
npm run dev
```

Frontend: http://localhost:3000

## 🛠️ TinyMCE Integration

TinyMCE endi CDN orqali yuklanadi, shuning uchun qo'shimcha sozlash talab qilinmaydi. Agar internet orqali yuklashda muammo bo'lsa:

1. **Alternative CDN**: Kod avtomatik ravishda fallback CDN dan foydalanadi
2. **Local setup**: Agar local TinyMCE kerak bo'lsa, `tinymce` paketini o'rnatish mumkin

```bash
cd frontend
npm install tinymce @tinymce/tinymce-react
```

## 🔍 Common Issues va Yechimlar

### 1. Database Connection Error

**Xatolik**: `SQLSTATE[HY000] [2002] Connection refused`

**Yechim**:
```bash
# MySQL server ishlayotganini tekshiring
sudo systemctl status mysql
sudo systemctl start mysql

# Database ma'lumotlarini tekshiring
php artisan tinker
DB::connection()->getPdo();
```

### 2. CORS Error

**Xatolik**: `Access-Control-Allow-Origin` header is missing

**Yechim**:
1. `.env` faylda CORS domenlarini tekshiring:
```env
SANCTUM_STATEFUL_DOMAINS=localhost:3000
SESSION_DOMAIN=localhost
```

2. `config/cors.php` ni tekshiring:
```php
'paths' => ['api/*', 'sanctum/csrf-cookie'],
'allowed_methods' => ['*'],
'allowed_origins' => ['http://localhost:3000'],
'allowed_headers' => ['*'],
```

### 3. TinyMCE Load Error

**Xatolik**: TinyMCE editor yuklanmaydi

**Yechim**:
1. Internet connection ni tekshiring
2. Browser console ni tekshiring (F12)
3. CDN bloklangan bo'lishi mumkin, VPN yoki alternative DNS dan foydalaning

### 4. Authentication Issues

**Xatolik**: Login yoki register ishlamaydi

**Yechim**:
```bash
# Cache ni tozalash
php artisan cache:clear
php artisan config:clear
php artisan view:clear

# Session ni tozalash
php artisan session:clear
```

### 5. File Permissions

**Xatolik**: Storage papkasiga yozib bo'lmaydi

**Yechim**:
```bash
# Ubuntu/Debian
sudo chown -R www-data:www-data storage
sudo chmod -R 755 storage
sudo chmod -R 777 storage/framework/cache
sudo chmod -R 777 storage/logs

# MacOS
sudo chmod -R 755 storage
sudo chmod -R 777 storage/framework/cache
sudo chmod -R 777 storage/logs
```

## 🧪 Testing

### Backend Testing
```bash
# PHPUnit testlari
php artisan test

# API endpointlarni test qilish
curl http://localhost:8000/api/v1/posts
curl http://localhost:8000/api/v1/categories
curl http://localhost:8000/api/v1/tags
```

### Frontend Testing
```bash
# Build test
npm run build

# Linting
npm run lint
```

## 📊 Debugging

### Backend Debugging
```bash
# Loglarni kuzatish
tail -f storage/logs/laravel.log

# Route list
php artisan route:list

# Model relations
php artisan model:show User
```

### Frontend Debugging
1. Browser dev tools (F12)
2. React Developer Tools
3. Network tab API call larni tekshirish

## 🔐 Security Notes

- `.env` faylni hech qachon GitHub ga yuklamang
- Local development uchun `APP_DEBUG=true` bo'lishi kerak
- Production da `APP_KEY` ni o'zgartiring
- Passwordlarni kuchli qiling

## 🚀 Deployment

Local development tayyor! Endi:

1. Backend: http://localhost:8000
2. Frontend: http://localhost:3000
3. API Docs: http://localhost:3000/api-docs

Post yaratish test qilish uchun:
1. Avval ro'yxatdan o'ting (http://localhost:3000/auth/register)
2. Keyin post yaratishga o'ting (http://localhost:3000/posts/create)

## 📞 Support

Agar muammolar yuzaga kelsa:
1. Console loglarini tekshiring
2. Laravel loglarini ko'ring: `storage/logs/laravel.log`
3. GitHub issues yaratish
