# KnowHub Community - Shared Hosting Setup Guide

## 📋 Shared Hostingga O'rnatish Bo'yicha Qo'llanma

### 1. Talablar
- PHP 8.2+
- MySQL 5.7+ yoki MariaDB 10.3+
- Composer (agar mavjud bo'lsa)
- cPanel yoki boshqa hosting panel

### 2. Fayllarni Yuklash

#### Backend (Laravel)
```bash
# Loyihani zip qilib yuklang
zip -r knowhub-backend.zip . -x "node_modules/*" "vendor/*" ".git/*"
```

1. cPanel File Manager orqali `public_html` papkasiga yuklang
2. Zip faylni ochib oling
3. Laravel loyihasining `public` papkasidagi barcha fayllarni `public_html` ga ko'chiring
4. Qolgan fayllarni `public_html` dan tashqariga (masalan `knowhub` papkasiga) ko'chiring

#### Frontend (Next.js)
```bash
# Frontend ni build qiling
cd frontend
npm run build
npm run export  # Static export uchun
```

1. `out` papkasidagi fayllarni alohida subdomain yoki papkaga yuklang
2. Yoki CDN orqali serve qiling

### 3. Database Setup

#### MySQL Database Yaratish
1. cPanel da MySQL Databases bo'limiga kiring
2. Yangi database yarating: `knowhub_db`
3. Yangi user yarating va database ga ruxsat bering
4. Database ma'lumotlarini yozib oling

#### Migration va Seeding
```bash
# SSH orqali (agar mavjud bo'lsa)
php artisan migrate --seed

# Yoki SQL faylni import qiling
mysql -u username -p knowhub_db < database/knowhub_structure.sql
```

### 4. Environment Configuration

#### .env fayl sozlash
```env
APP_NAME="KnowHub Community"
APP_ENV=production
APP_KEY=base64:GENERATED_KEY_HERE
APP_DEBUG=false
APP_URL=https://yourdomain.com

DB_CONNECTION=mysql
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=knowhub_db
DB_USERNAME=your_db_user
DB_PASSWORD=your_db_password

CACHE_DRIVER=file
SESSION_DRIVER=file
QUEUE_CONNECTION=database

MAIL_MAILER=smtp
MAIL_HOST=your_smtp_host
MAIL_PORT=587
MAIL_USERNAME=your_email
MAIL_PASSWORD=your_password
MAIL_ENCRYPTION=tls
```

### 5. File Permissions

```bash
# Storage va cache papkalariga yozish ruxsati
chmod -R 755 storage/
chmod -R 755 bootstrap/cache/
```

### 6. Composer Dependencies

#### Agar Composer mavjud bo'lsa:
```bash
composer install --no-dev --optimize-autoloader
```

#### Agar Composer mavjud bo'lmasa:
1. Local da `composer install --no-dev` bajaring
2. `vendor` papkasini ham yuklang

### 7. Laravel Optimizatsiya

```bash
# Config cache
php artisan config:cache

# Route cache
php artisan route:cache

# View cache
php artisan view:cache

# Autoloader optimize
composer dump-autoload --optimize
```

### 8. Frontend Configuration

#### Next.js uchun .env.local
```env
NEXT_PUBLIC_API_URL=https://yourdomain.com/api/v1
NEXT_PUBLIC_APP_URL=https://yourdomain.com
```

### 9. SSL Certificate

1. cPanel da SSL/TLS bo'limiga kiring
2. Let's Encrypt yoki boshqa SSL sertifikat o'rnating
3. HTTPS ga majburiy yo'naltirish yoqing

### 10. Cron Jobs (agar kerak bo'lsa)

```bash
# cPanel Cron Jobs bo'limida qo'shing
* * * * * cd /path/to/your/project && php artisan schedule:run >> /dev/null 2>&1
```

### 11. Subdomain Setup (Frontend uchun)

1. cPanel da Subdomains bo'limiga kiring
2. `app` yoki `frontend` subdomain yarating
3. Frontend fayllarini shu subdomain papkasiga yuklang

### 12. API CORS Configuration

#### config/cors.php
```php
'allowed_origins' => [
    'https://yourdomain.com',
    'https://app.yourdomain.com',
    'https://www.yourdomain.com',
],
```

### 13. Performance Optimization

#### .htaccess optimizatsiya
```apache
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

# Browser caching
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
```

### 14. Monitoring va Logs

#### Error Logging
```php
// config/logging.php
'channels' => [
    'single' => [
        'driver' => 'single',
        'path' => storage_path('logs/laravel.log'),
        'level' => env('LOG_LEVEL', 'error'),
    ],
],
```

### 15. Backup Strategy

#### Database Backup
```bash
# Cron job orqali
0 2 * * * mysqldump -u username -p password knowhub_db > /path/to/backup/knowhub_$(date +\%Y\%m\%d).sql
```

#### File Backup
```bash
# Storage papkasini backup qilish
0 3 * * * tar -czf /path/to/backup/storage_$(date +\%Y\%m\%d).tar.gz /path/to/storage/
```

### 16. Security Checklist

- [ ] .env fayl himoyalangan
- [ ] Debug mode o'chirilgan
- [ ] SSL sertifikat o'rnatilgan
- [ ] Database parollari kuchli
- [ ] File permissions to'g'ri sozlangan
- [ ] Sensitive fayllar himoyalangan

### 17. Testing

1. Saytga kirish va asosiy funksiyalarni tekshirish
2. API endpointlarni test qilish
3. Database connection tekshirish
4. Email yuborish test qilish
5. File upload test qilish

### 18. Common Issues va Yechimlar

#### 500 Internal Server Error
- .env fayl mavjudligini tekshiring
- File permissions ni tekshiring
- Error loglarni ko'ring

#### Database Connection Error
- Database credentials ni tekshiring
- Database server holatini tekshiring

#### CORS Errors
- config/cors.php ni tekshiring
- Frontend URL larni to'g'ri sozlang

### 19. Support

Muammolar yuzaga kelsa:
- Error loglarni tekshiring: `storage/logs/laravel.log`
- Hosting provider support ga murojaat qiling
- KnowHub Community support: support@knowhub.uz

---

**Eslatma**: Shared hosting da ba'zi cheklovlar bo'lishi mumkin (memory limit, execution time, etc.). Agar muammolar bo'lsa, VPS yoki dedicated server ga o'tishni ko'rib chiqing.