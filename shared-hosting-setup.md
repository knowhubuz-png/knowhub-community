# KnowHub Community - Shared Hosting Setup Guide

## 📋 Shared Hostingga O'rnatish Bo'yicha Qo'llanma

### 1. Talablar
- PHP 8.2+
- MySQL 5.7+ yoki MariaDB 10.3+
- Composer (agar mavjud bo'lsa)
- cPanel yoki boshqa hosting panel
- Kamida 512MB RAM
- 1GB disk space

### 2. Fayllarni Yuklash

#### Backend (Laravel)
```bash
# Loyihani zip qilib yuklang (node_modules va vendor siz)
zip -r knowhub-backend.zip . -x "node_modules/*" "vendor/*" ".git/*" "storage/logs/*"
```

**cPanel orqali yuklash:**
1. cPanel File Manager ga kiring
2. `public_html` papkasiga zip faylni yuklang
3. Zip faylni ochib oling
4. Laravel loyihasining `public` papkasidagi barcha fayllarni `public_html` ga ko'chiring
5. Qolgan fayllarni `public_html` dan tashqariga (masalan `knowhub` papkasiga) ko'chiring

#### Papka tuzilishi:
```
/home/username/
├── public_html/           # Laravel public papkasi kontenti
│   ├── index.php
│   ├── .htaccess
│   └── ...
├── knowhub/              # Laravel asosiy papkasi
│   ├── app/
│   ├── config/
│   ├── database/
│   └── ...
└── vendor/               # Composer dependencies
```

### 3. Database Setup

#### MySQL Database Yaratish
1. cPanel da MySQL Databases bo'limiga kiring
2. Yangi database yarating: `username_knowhub`
3. Yangi user yarating va database ga to'liq ruxsat bering
4. Database ma'lumotlarini yozib oling

#### Migration va Seeding
```bash
# SSH orqali (agar mavjud bo'lsa)
cd /home/username/knowhub
php artisan migrate --seed

# Yoki phpMyAdmin orqali SQL import qiling
```

### 4. Environment Configuration

#### .env fayl sozlash
```env
APP_NAME="KnowHub Community"
APP_ENV=production
APP_KEY=base64:GENERATED_KEY_HERE
APP_DEBUG=false
APP_URL=https://yourdomain.com

# Database
DB_CONNECTION=mysql
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=username_knowhub
DB_USERNAME=username_dbuser
DB_PASSWORD=your_strong_password

# Cache va Session (file-based shared hosting uchun)
CACHE_DRIVER=file
SESSION_DRIVER=file
QUEUE_CONNECTION=database

# Mail
MAIL_MAILER=smtp
MAIL_HOST=your_smtp_host
MAIL_PORT=587
MAIL_USERNAME=your_email
MAIL_PASSWORD=your_password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@yourdomain.com
MAIL_FROM_NAME="KnowHub Community"

# Security
SESSION_SECURE_COOKIE=true
SESSION_SAME_SITE=lax
```

### 5. File Permissions

```bash
# SSH orqali
chmod -R 755 storage/
chmod -R 755 bootstrap/cache/
chmod -R 777 storage/framework/cache/
chmod -R 777 storage/framework/sessions/
chmod -R 777 storage/framework/views/
chmod -R 777 storage/logs/

# cPanel File Manager orqali
# storage va bootstrap/cache papkalarini 755 ga o'rnating
# storage/framework/* papkalarini 777 ga o'rnating
```

### 6. Composer Dependencies

#### Agar SSH mavjud bo'lsa:
```bash
cd /home/username/knowhub
composer install --no-dev --optimize-autoloader
```

#### Agar SSH mavjud bo'lmasa:
1. Local kompyuterda `composer install --no-dev` bajaring
2. `vendor` papkasini ham zip ga qo'shib yuklang
3. Yoki hosting provider dan Composer o'rnatishni so'rang

### 7. Laravel Optimizatsiya

```bash
# Config cache
php artisan config:cache

# Route cache
php artisan route:cache

# View cache
php artisan view:cache

# Storage link
php artisan storage:link

# Autoloader optimize
composer dump-autoload --optimize
```

### 8. .htaccess Optimizatsiya

#### public_html/.htaccess
```apache
<IfModule mod_rewrite.c>
    RewriteEngine On
    
    # Laravel public papkasiga yo'naltirish
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteRule ^(.*)$ index.php [QSA,L]
    
    # Security headers
    <IfModule mod_headers.c>
        Header always set X-Content-Type-Options nosniff
        Header always set X-Frame-Options DENY
        Header always set X-XSS-Protection "1; mode=block"
        Header always set Referrer-Policy "strict-origin-when-cross-origin"
    </IfModule>
    
    # Gzip compression
    <IfModule mod_deflate.c>
        AddOutputFilterByType DEFLATE text/plain
        AddOutputFilterByType DEFLATE text/html
        AddOutputFilterByType DEFLATE text/xml
        AddOutputFilterByType DEFLATE text/css
        AddOutputFilterByType DEFLATE application/xml
        AddOutputFilterByType DEFLATE application/xhtml+xml
        AddOutputFilterByType DEFLATE application/rss+xml
        AddOutputFilterByType DEFLATE application/javascript
        AddOutputFilterByType DEFLATE application/x-javascript
        AddOutputFilterByType DEFLATE application/json
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
        ExpiresByType image/svg+xml "access plus 1 year"
    </IfModule>
    
    # Prevent access to sensitive files
    <FilesMatch "\.(env|log|sql|md)$">
        Order allow,deny
        Deny from all
    </FilesMatch>
</IfModule>
```

### 9. index.php Modifikatsiya

#### public_html/index.php
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

### 10. SSL Certificate

1. cPanel da SSL/TLS bo'limiga kiring
2. Let's Encrypt yoki boshqa SSL sertifikat o'rnating
3. HTTPS ga majburiy yo'naltirish yoqing
4. .htaccess ga HTTPS redirect qo'shing:

```apache
# HTTPS redirect
RewriteCond %{HTTPS} off
RewriteRule ^(.*)$ https://%{HTTP_HOST}%{REQUEST_URI} [L,R=301]
```

### 11. Cron Jobs (Background Tasks)

cPanel Cron Jobs bo'limida qo'shing:
```bash
# Har daqiqada Laravel scheduler
* * * * * cd /home/username/knowhub && php artisan schedule:run >> /dev/null 2>&1

# Har soatda cache tozalash
0 * * * * cd /home/username/knowhub && php artisan cache:clear >> /dev/null 2>&1

# Har kuni log tozalash
0 2 * * * find /home/username/knowhub/storage/logs -name "*.log" -mtime +7 -delete
```

### 12. Performance Optimization

#### config/cache.php
```php
'default' => env('CACHE_STORE', 'file'),
```

#### config/session.php
```php
'driver' => env('SESSION_DRIVER', 'file'),
```

#### config/queue.php
```php
'default' => env('QUEUE_CONNECTION', 'database'),
```

### 13. Error Handling

#### app/Exceptions/Handler.php ga qo'shing:
```php
public function register(): void
{
    $this->reportable(function (Throwable $e) {
        if (app()->environment('production')) {
            Log::error('Production Error: ' . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);
        }
    });
}
```

### 14. Security Checklist

- [ ] .env fayl himoyalangan (public_html dan tashqarida)
- [ ] Debug mode o'chirilgan (APP_DEBUG=false)
- [ ] SSL sertifikat o'rnatilgan
- [ ] Database parollari kuchli
- [ ] File permissions to'g'ri sozlangan
- [ ] Sensitive fayllar himoyalangan (.htaccess orqali)
- [ ] Error reporting production uchun sozlangan

### 15. Testing

1. **Asosiy sahifani tekshiring:** https://yourdomain.com
2. **Database connection:** Postlar ko'rinishini tekshiring
3. **File permissions:** Rasm yuklash test qiling
4. **Email:** Ro'yxatdan o'tish test qiling
5. **Cache:** Sahifa tezligini tekshiring

### 16. Monitoring

#### Log fayllarni kuzatish:
```bash
# Laravel logs
tail -f /home/username/knowhub/storage/logs/laravel.log

# Apache/Nginx error logs
tail -f /home/username/logs/error_log
```

#### Performance monitoring:
- Google PageSpeed Insights
- GTmetrix
- Pingdom

### 17. Backup Strategy

#### Avtomatik backup (cron job):
```bash
# Database backup (har kuni)
0 2 * * * mysqldump -u username -p'password' username_knowhub > /home/username/backups/db_$(date +\%Y\%m\%d).sql

# Files backup (haftada bir marta)
0 3 * * 0 tar -czf /home/username/backups/files_$(date +\%Y\%m\%d).tar.gz /home/username/knowhub/storage/
```

### 18. Common Issues va Yechimlar

#### 500 Internal Server Error
1. .env fayl mavjudligini tekshiring
2. File permissions ni tekshiring (755/777)
3. Error loglarni ko'ring
4. PHP versiyasini tekshiring

#### Database Connection Error
1. Database credentials ni tekshiring
2. Database server holatini tekshiring
3. MySQL user permissions ni tekshiring

#### Composer Autoload Error
```bash
composer dump-autoload --optimize
```

#### Storage Link Error
```bash
php artisan storage:link
```

### 19. Performance Tips

1. **Caching:** File cache ishlatish
2. **Database:** Index qo'shish
3. **Images:** Optimallashtirilgan rasmlar
4. **CDN:** Static fayllar uchun CDN ishlatish
5. **Minification:** CSS/JS minify qilish

### 20. Support

Muammolar yuzaga kelsa:
- Error loglarni tekshiring: `storage/logs/laravel.log`
- Hosting provider support ga murojaat qiling
- KnowHub Community support: support@knowhub.uz
- Telegram: @knowhub_support

---

**Eslatma**: Shared hosting da ba'zi cheklovlar bo'lishi mumkin (memory limit, execution time, etc.). Agar jiddiy muammolar bo'lsa, VPS yoki dedicated server ga o'tishni ko'rib chiqing.

### 21. Shared Hosting Providers

**Tavsiya etilgan providerlar:**
- **Beget.com** - PHP 8.2+, MySQL, SSH
- **TimeWeb.ru** - Laravel support
- **Hostinger** - Optimized for Laravel
- **A2 Hosting** - Developer friendly

**O'zbek providerlar:**
- **UZINFOCOM** - Local support
- **Perfectum Web** - Laravel hosting
- **Digital Ocean** - VPS (kelajak uchun)