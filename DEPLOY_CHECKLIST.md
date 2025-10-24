# VPS Deploy Checklist

## Pre-Deploy Tekshirish

- [x] Barcha buglar tuzatildi
- [x] Laravel backend build muvaffaqiyatli
- [x] Next.js frontend build muvaffaqiyatli
- [x] CORS sozlamalari yangilandi
- [x] Auth response formati to'g'rilandi
- [x] Admin panel tuzatildi
- [x] Profile sahifalar ishlaydi
- [x] Leaderboard sahifasi ishlaydi

---

## Backend Deploy (Laravel)

### 1. Git Pull
```bash
cd /path/to/your/project
git pull origin main
```

### 2. Dependencies
```bash
composer install --no-dev --optimize-autoloader
```

### 3. Environment Configuration

**MUHIM**: `.env` faylini tahrirlang:

```bash
nano .env
```

Quyidagilarni o'zgartiring/qo'shing:

```env
# Production settings
APP_ENV=production
APP_DEBUG=false
APP_URL=https://your-api-domain.com

# CORS - Frontend domainlaringizni qo'shing
CORS_ALLOWED_ORIGINS="https://your-frontend.com,https://api.your-domain.com"

# Sanctum - Frontend domainlaringizni qo'shing (https:// siz)
SANCTUM_STATEFUL_DOMAINS="your-frontend.com,api.your-domain.com,localhost,localhost:3000"

# Frontend URL
FRONTEND_URL=https://your-frontend.com
```

### 4. Database Migration (agar kerak bo'lsa)
```bash
php artisan migrate --force
```

### 5. Admin User Yaratish/Yangilash

**Variant A: Tinker orqali**
```bash
php artisan tinker
User::where('id', 1)->update(['is_admin' => true]);
# yoki
User::where('email', 'admin@knowhub.uz')->update(['is_admin' => true]);
exit
```

**Variant B: Direct SQL (MySQL/PostgreSQL)** (qiyinroq)
```sql
UPDATE users SET is_admin = 1 WHERE id = 1;
-- yoki
UPDATE users SET is_admin = 1 WHERE email = 'admin@knowhub.uz';
```

### 6. Cache Yangilash
```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan optimize
```

### 7. Storage Link (agar kerak bo'lsa)
```bash
php artisan storage:link
```

### 8. Permissions
```bash
chmod -R 755 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache
```

### 9. Services Restart
```bash
# Nginx
sudo systemctl restart nginx

# PHP-FPM (versiyangizga qarab)
sudo systemctl restart php8.2-fpm
# yoki
sudo systemctl restart php8.1-fpm

# Supervisor (agar queue worker ishlatayotgan bo'lsangiz)
sudo supervisorctl restart all
```

---

## Frontend Deploy (Next.js)

### 1. Git Pull
```bash
cd /path/to/your/project/frontend
git pull origin main
```

### 2. Dependencies
```bash
npm install
```

### 3. Environment Configuration

**MUHIM**: `.env.local` faylini yarating/tahrirlang:

```bash
nano .env.local
```

Quyidagini qo'shing:

```env
NEXT_PUBLIC_API_URL=https://your-api-domain.com/api/v1
```

### 4. Build
```bash
# Agar favicon.ico muammosi bo'lsa:
rm -f src/app/favicon.ico

# Build
npm run build
```

### 5. PM2 Restart (agar PM2 ishlatayotgan bo'lsangiz)
```bash
pm2 restart frontend
# yoki barcha processlarni
pm2 restart all

# Statusni tekshirish
pm2 status
pm2 logs frontend
```

**Agar PM2 ishlatmayotgan bo'lsangiz** (Next.js standalone):
```bash
# Build papkasidan ishga tushirish
npm start
# yoki production server
node server.js
```

---

## Test Qilish

### 1. Backend API Test

**Health Check**:
```bash
curl https://your-api-domain.com/api/v1/stats/public
```

**Login Test**:
```bash
curl -X POST https://your-api-domain.com/api/v1/auth/email/login \
  -H "Content-Type: application/json" \
  -d '{"email":"your-email@example.com","password":"your-password"}'
```

Response da `is_admin: true` borligini tekshiring (admin user uchun).

**Profile Test**:
```bash
curl https://your-api-domain.com/api/v1/users/admin
```

### 2. Frontend Test

Browser da quyidagi sahifalarni oching:

- **Home**: `https://your-frontend.com/`
- **Admin Panel**: `https://your-frontend.com/admin`
  - Admin user bilan login qiling
  - Dashboard ko'rinishi kerak
- **Profile**: `https://your-frontend.com/profile/admin`
  - User profili to'liq ko'rinishi kerak
- **Leaderboard**: `https://your-frontend.com/leaderboard`
  - Top userlar ro'yxati ko'rinishi kerak

### 3. Browser Console Tekshirish

Browser da F12 bosing:
- **Console** tab: Xatolar yo'qligini tekshiring
- **Network** tab: API requestlar 200/201 status qaytarganini tekshiring
- CORS xatolari bo'lmasligi kerak

---

## Troubleshooting

### CORS Xatolari

**Agar browserda CORS xatolari ko'rsatilsa:**

1. `.env` faylida CORS sozlamalarini tekshiring:
```bash
cat .env | grep CORS
cat .env | grep SANCTUM
```

2. Cache tozalang:
```bash
php artisan config:clear
php artisan config:cache
```

3. Nginx/Apache CORS headerlarini tekshiring (agar qo'shimcha sozlagan bo'lsangiz)

### Admin Panelga Kirish Muammosi

1. **Database tekshirish**:
```sql
SELECT id, name, email, is_admin FROM users WHERE email = 'your-email@example.com';
```
`is_admin` 1 bo'lishi kerak.

2. **Login response tekshirish**:
Browser Console > Network > Login request > Response tab
`is_admin: true` borligini tekshiring.

3. **Frontend localStorage tekshirish**:
Browser Console da:
```javascript
localStorage.getItem('auth_token')
```
Token borligini tekshiring.

### Profile/Leaderboard Sahifalar Ochilmasa

1. **API endpoint test qilish**:
```bash
curl https://your-api-domain.com/api/v1/users/admin
curl https://your-api-domain.com/api/v1/users/leaderboard
```

2. **Frontend .env.local tekshirish**:
```bash
cat frontend/.env.local
```
`NEXT_PUBLIC_API_URL` to'g'ri sozlanganini tekshiring.

3. **Next.js logs tekshirish**:
```bash
pm2 logs frontend
```

---

## Final Checklist

Deploy qilgandan keyin quyidagilarni tekshiring:

- [ ] Backend API ishlayapti (`/api/v1/stats/public`)
- [ ] Login ishlayapti (token qaytaradi)
- [ ] Admin user bilan login qilish mumkin
- [ ] Admin panel ochiladi va dashboard ko'rinadi
- [ ] Profile sahifalar ishlaydi
- [ ] Leaderboard sahifasi ishlaydi
- [ ] CORS xatolari yo'q
- [ ] Browser console da xatolar yo'q
- [ ] SSL/HTTPS ishlayapti
- [ ] Nginx/Apache to'g'ri sozlangan

---

## Qo'shimcha

### Nginx Configuration Misol

```nginx
server {
    listen 443 ssl http2;
    server_name your-api-domain.com;

    root /path/to/your/project/public;
    index index.php;

    # SSL certificates
    ssl_certificate /path/to/cert.pem;
    ssl_certificate_key /path/to/key.pem;

    # Laravel specific
    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
        fastcgi_index index.php;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    # Security headers
    add_header X-Frame-Options "SAMEORIGIN" always;
    add_header X-Content-Type-Options "nosniff" always;
    add_header X-XSS-Protection "1; mode=block" always;
}
```

### PM2 Configuration Misol

```json
{
  "apps": [
    {
      "name": "frontend",
      "cwd": "/path/to/your/project/frontend",
      "script": "npm",
      "args": "start",
      "env": {
        "NODE_ENV": "production",
        "PORT": 3000
      }
    }
  ]
}
```

---

## Murojaat

Muammo yuzaga kelsa:
1. Logs tekshiring: `tail -f storage/logs/laravel.log`
2. PM2 logs: `pm2 logs frontend`
3. Nginx error logs: `tail -f /var/log/nginx/error.log`

Barcha tuzatishlar haqida to'liq ma'lumot: `FIXES_APPLIED.md` faylida.
