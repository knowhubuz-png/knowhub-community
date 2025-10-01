# 🏠 KnowHub Community - Shared Hosting O'rnatish Qo'llanmasi

## 📋 Talablar

- **PHP:** 8.2 yoki undan yuqori
- **MySQL:** 5.7 yoki undan yuqori  
- **Disk:** Kamida 500MB bo'sh joy
- **RAM:** Kamida 256MB
- **cPanel** yoki boshqa hosting panel

## 🚀 Tezkor O'rnatish

### 1. Fayllarni Tayyorlash

```bash
# Loyihani zip qilib tayyorlash
zip -r knowhub-v1.0.0.zip . -x "node_modules/*" "vendor/*" ".git/*" "frontend/node_modules/*" "frontend/.next/*"
```

### 2. Shared Hostingga Yuklash

1. **cPanel File Manager** ga kiring
2. `public_html` papkasiga zip faylni yuklang
3. Zip faylni ochib oling
4. **Muhim:** Laravel `public` papkasidagi barcha fayllarni `public_html` ga ko'chiring
5. Qolgan Laravel fayllarni `public_html` dan tashqariga (masalan `knowhub` papkasiga) ko'chiring

### 3. Papka Tuzilishi

```
/home/username/
├── public_html/           # Laravel public papkasi
│   ├── index.php
│   ├── .htaccess
│   ├── css/
│   ├── js/
│   └── storage/
├── knowhub/              # Laravel asosiy papkasi
│   ├── app/
│   ├── config/
│   ├── database/
│   ├── routes/
│   └── ...
└── vendor/               # Composer dependencies
```

### 4. Database Yaratish

1. **cPanel MySQL Databases** bo'limiga kiring
2. Yangi database yarating: `username_knowhub`
3. Yangi user yarating va database ga to'liq ruxsat bering
4. Database ma'lumotlarini yozib oling

### 5. Environment Sozlash

`knowhub/.env` faylini yarating:

```env
APP_NAME="KnowHub Community"
APP_ENV=production
APP_KEY=base64:GENERATED_KEY_HERE
APP_DEBUG=false
APP_URL=https://yourdomain.com

DB_CONNECTION=mysql
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=username_knowhub
DB_USERNAME=username_dbuser
DB_PASSWORD=your_strong_password

CACHE_DRIVER=file
SESSION_DRIVER=file
QUEUE_CONNECTION=database

MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your_email
MAIL_PASSWORD=your_password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@yourdomain.com
MAIL_FROM_NAME="KnowHub Community"

SESSION_SECURE_COOKIE=true
SESSION_SAME_SITE=lax
```

### 6. index.php Modifikatsiya

`public_html/index.php` ni tahrirlang:

```php
<?php

use Illuminate\Foundation\Application;
use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

// Shared hosting uchun path adjustment
$maintenance = __DIR__.'/../knowhub/storage/framework/maintenance.php';
if (file_exists($maintenance)) {
    require $maintenance;
}

// Autoloader
require __DIR__.'/../vendor/autoload.php';

// Bootstrap Laravel
$app = require_once __DIR__.'/../knowhub/bootstrap/app.php';

$app->handleRequest(Request::capture());
```

### 7. Avtomatik O'rnatish (Tavsiya etiladi)

Brauzerda ochib, qadamlarni bajaring:
```
https://yourdomain.com/shared-hosting-deploy.php
```

### 8. Manual O'rnatish

SSH orqali (agar mavjud bo'lsa):

```bash
# Composer dependencies
cd /home/username/knowhub
composer install --no-dev --optimize-autoloader

# Laravel setup
php artisan key:generate
php artisan migrate --force
php artisan db:seed --force
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan storage:link

# File permissions
chmod -R 755 storage/
chmod -R 755 bootstrap/cache/
chmod -R 777 storage/framework/cache/
chmod -R 777 storage/framework/sessions/
chmod -R 777 storage/framework/views/
chmod -R 777 storage/logs/
```

### 9. Frontend Build (Agar Node.js mavjud bo'lsa)

```bash
cd frontend
npm install
npm run build
cp -r out/* ../public_html/
```

## 🔧 Muammolarni Hal Qilish

### 500 Internal Server Error
1. `.env` fayl mavjudligini tekshiring
2. File permissions ni tekshiring (755/777)
3. `storage/logs/laravel.log` ni ko'ring
4. PHP versiyasini tekshiring

### Database Connection Error
1. Database credentials ni tekshiring
2. Database server holatini tekshiring
3. MySQL user permissions ni tekshiring

### Composer Autoload Error
```bash
composer dump-autoload --optimize
```

### Storage Link Error
```bash
php artisan storage:link
```

## 📊 Performance Optimizatsiya

### 1. Caching
```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### 2. Database Optimizatsiya
```sql
-- Index qo'shish
ALTER TABLE posts ADD INDEX idx_status_created (status, created_at);
ALTER TABLE posts ADD INDEX idx_user_status (user_id, status);
ALTER TABLE comments ADD INDEX idx_post_created (post_id, created_at);
```

### 3. .htaccess Optimizatsiya
```apache
# Gzip compression
<IfModule mod_deflate.c>
    AddOutputFilterByType DEFLATE text/plain text/html text/xml text/css application/xml application/xhtml+xml application/rss+xml application/javascript application/x-javascript application/json
</IfModule>

# Cache static files
<IfModule mod_expires.c>
    ExpiresActive on
    ExpiresByType text/css "access plus 1 year"
    ExpiresByType application/javascript "access plus 1 year"
    ExpiresByType image/png "access plus 1 year"
    ExpiresByType image/jpg "access plus 1 year"
    ExpiresByType image/jpeg "access plus 1 year"
    ExpiresByType image/gif "access plus 1 year"
</IfModule>
```

## 🔒 Xavfsizlik

### 1. File Permissions
```bash
# Secure permissions
find . -type f -exec chmod 644 {} \;
find . -type d -exec chmod 755 {} \;
chmod -R 777 storage/
chmod -R 777 bootstrap/cache/
```

### 2. Sensitive Files Protection
```apache
# .htaccess ga qo'shing
<FilesMatch "\.(env|log|sql|md)$">
    Order allow,deny
    Deny from all
</FilesMatch>
```

## 📞 Support

Muammolar yuzaga kelsa:
- **Email:** support@knowhub.uz
- **Telegram:** @knowhub_support
- **GitHub Issues:** https://github.com/knowhub-dev/knowhub-community/issues

## 🎉 Muvaffaqiyatli O'rnatish

O'rnatish yakunlangandan so'ng:
1. `https://yourdomain.com` ga tashrif buyuring
2. Ro'yxatdan o'ting
3. Birinchi postni yarating
4. Admin panel: `https://yourdomain.com/admin`

**Eslatma:** Deploy skriptini o'rnatish yakunlangandan so'ng o'chiring yoki nomini o'zgartiring!