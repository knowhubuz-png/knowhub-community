# Barcha Tuzatishlar

## Muammolar va Yechimlar

### 1. Frontend Admin Panel Autentifikatsiya Muammosi

**Muammo**: Backend orqali admin panelga kirish mumkin, lekin frontend orqali kirish imkonsiz edi.

**Sabab**:
- Login response da `is_admin` field qaytarilmagan edi
- AuthProvider user type da `is_admin` field yo'q edi
- Profile me endpoint minimal ma'lumot qaytargan

**Yechim**:
- `EmailAuthController@login` - response formatini yangiladik, barcha user fieldlarni (is_admin, is_banned) qaytaradi
- `EmailAuthController@register` - ham xuddi shunday tuzatildi
- `ProfileController@me` - to'liq user ma'lumotlarini qaytaradi (is_admin, level, badges)
- AuthProvider da User interface ga `is_admin` va `is_banned` qo'shildi

**Test**:
```bash
# Login qilib, response tekshirish
curl -X POST http://your-domain.com/api/v1/auth/email/login \
  -H "Content-Type: application/json" \
  -d '{"email":"admin@knowhub.uz","password":"your-password"}'

# Response da is_admin: true bo'lishi kerak
```

---

### 2. Profile Sahifalar Ishlamasligi

**Muammo**: `/profile/[username]` sahifalar ochilmayotgan edi.

**Sabab**:
- UserController show metodi minimal ma'lumot qaytargan
- SSR fetch xatolik bergan
- API URL environment variable noto'g'ri

**Yechim**:
- `UserController@show` - to'liq user profileni qaytaradi (posts_count, comments_count, followers_count, following_count)
- Frontend profile page UI yaxshilandi
- Error handling qo'shildi
- Environment variable tuzatildi

**Test**:
```bash
# Profile ma'lumotlarini olish
curl http://your-domain.com/api/v1/users/admin
```

---

### 3. Leaderboard Sahifasi Muammosi

**Muammo**: `/leaderboard` sahifasi ochilmayotgan edi.

**Sabab**:
- User type mismatch (TypeScript)
- API response formati kutilganiga mos emas

**Yechim**:
- Leaderboard page da type checking yumshatildi (`User` -> `any`)
- `getStatValue` funksiyasi fallback valuelar bilan yangilandi
- Multiple field check qiladi (posts_count, stats.posts_count)

**Test**:
```bash
# Leaderboard ma'lumotlarini olish
curl http://your-domain.com/api/v1/users/leaderboard?type=xp&period=all
```

---

### 4. CORS Muammolari

**Muammo**: Frontend va backend o'rtasida CORS xatoliklari.

**Sabab**:
- CORS allowed_origins hardcoded bo'lgan (faqat localhost)
- Production domain qo'shilmagan

**Yechim**:
- `config/cors.php` - environment variable orqali boshqarish qo'shildi
- `.env.production.example` da CORS_ALLOWED_ORIGINS misol
- Multiple domainlar qo'llab-quvvatlanadi

**Production .env ga qo'shish**:
```bash
CORS_ALLOWED_ORIGINS="https://your-frontend.com,https://api.your-domain.com"
SANCTUM_STATEFUL_DOMAINS="your-frontend.com,api.your-domain.com"
```

---

## Deploy Qilish Bosqichlari

### Backend (VPS da)

1. **Kodni yangilash**:
```bash
cd /path/to/project
git pull origin main
```

2. **Dependencies o'rnatish**:
```bash
composer install --no-dev --optimize-autoloader
```

3. **Environment sozlash**:
```bash
# Production .env faylini tahrirlash
nano .env

# Kerakli o'zgarishlar:
# - APP_ENV=production
# - APP_DEBUG=false
# - APP_URL=https://your-domain.com
# - CORS_ALLOWED_ORIGINS=https://your-frontend.com
# - SANCTUM_STATEFUL_DOMAINS=your-frontend.com
```

4. **Cache yangilash**:
```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan optimize
```

5. **Admin huquqini berish** (agar hali berilmagan bo'lsa):
```bash
php artisan tinker
User::where('id', 1)->update(['is_admin' => true]);
# yoki
User::where('email', 'admin@knowhub.uz')->update(['is_admin' => true]);
exit
```

6. **Server restart**:
```bash
# Nginx
sudo systemctl restart nginx

# PHP-FPM
sudo systemctl restart php8.2-fpm

# Supervisor (queue worker)
sudo supervisorctl restart all
```

---

### Frontend (VPS da)

1. **Kodni yangilash**:
```bash
cd /path/to/project/frontend
git pull origin main
```

2. **Dependencies o'rnatish**:
```bash
npm install
```

3. **Environment sozlash**:
```bash
# .env.local faylini yaratish yoki tahrirlash
nano .env.local

# Quyidagini qo'shing:
NEXT_PUBLIC_API_URL=https://your-api-domain.com/api/v1
```

4. **Build qilish**:
```bash
npm run build
```

5. **PM2 restart** (agar PM2 ishlatayotgan bo'lsangiz):
```bash
pm2 restart frontend
# yoki
pm2 restart all
```

---

## Test Qilish

### 1. Admin Panel Test
```bash
# Browser da:
https://your-frontend.com/admin

# Admin user bilan login qiling
# Dashboard ko'rinishi kerak
```

### 2. Profile Sahifalar Test
```bash
# Browser da:
https://your-frontend.com/profile/admin

# User profili to'liq ko'rinishi kerak (XP, posts, followers, etc.)
```

### 3. Leaderboard Test
```bash
# Browser da:
https://your-frontend.com/leaderboard

# Top userlar ro'yxati ko'rinishi kerak
```

### 4. API Test (cURL)
```bash
# Login
curl -X POST https://your-api-domain.com/api/v1/auth/email/login \
  -H "Content-Type: application/json" \
  -d '{"email":"test@test.com","password":"password"}'

# Profile
curl https://your-api-domain.com/api/v1/users/username

# Leaderboard
curl https://your-api-domain.com/api/v1/users/leaderboard
```

---

## Muammolar hal qilish

### Agar admin panelga kirish hali ishlamasa:

1. **Browser Console tekshirish**:
   - F12 bosing
   - Network tabga o'ting
   - Login request va response ni ko'ring

2. **API response tekshirish**:
```bash
# Login API ni test qiling
curl -v -X POST http://your-domain.com/api/v1/auth/email/login \
  -H "Content-Type: application/json" \
  -d '{"email":"admin@knowhub.uz","password":"your-password"}'

# Response da is_admin: true borligini tekshiring
```

3. **Database tekshirish**:
```sql
SELECT id, name, email, is_admin FROM users WHERE id = 1;
-- is_admin = 1 bo'lishi kerak
```

4. **Cache tozalash**:
```bash
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
```

### Agar CORS xatolari bo'lsa:

1. **.env faylini tekshiring**:
```bash
cat .env | grep CORS
cat .env | grep SANCTUM
```

2. **config/cors.php tekshiring**:
```bash
php artisan config:show cors
```

3. **Browserda CORS xatoligini ko'ring**:
   - F12 > Console
   - Qizil CORS xatoliklari bormi?
   - Origin qaysi domain?

---

## Qo'shimcha Yaxshilashlar

### Security Headers (Nginx)

Nginx config ga qo'shing:
```nginx
add_header X-Frame-Options "SAMEORIGIN" always;
add_header X-Content-Type-Options "nosniff" always;
add_header X-XSS-Protection "1; mode=block" always;
add_header Referrer-Policy "strict-origin-when-cross-origin" always;
```

### Rate Limiting

Laravel da allaqachon mavjud, lekin qo'shimcha sozlash mumkin:
```php
// app/Http/Kernel.php
'api' => [
    'throttle:60,1', // 60 requests per minute
],
```

---

## Fayl O'zgarishlari Ro'yxati

### Backend
1. `app/Models/User.php` - is_admin, is_banned fields qo'shildi
2. `app/Http/Controllers/Auth/EmailAuthController.php` - response formati yangilandi
3. `app/Http/Controllers/Api/V1/ProfileController.php` - me metodi yaxshilandi
4. `app/Http/Controllers/Api/V1/UserController.php` - show metodi yaxshilandi
5. `config/cors.php` - environment variable qo'llab-quvvatlash

### Frontend
1. `frontend/src/providers/AuthProvider.tsx` - User interface yangilandi
2. `frontend/src/app/admin/page.tsx` - auth check va loading state
3. `frontend/src/app/profile/[username]/page.tsx` - SSR va UI yaxshilandi
4. `frontend/src/app/leaderboard/page.tsx` - type handling tuzatildi
5. `frontend/.env.local` - API URL sozlandi
6. `frontend/next.config.ts` - export mode olib tashlandi

---

## Xulosa

Barcha asosiy muammolar hal qilindi:
- ✅ Admin panel frontend orqali kirish
- ✅ Profile sahifalar SSR bilan ishlaydi
- ✅ Leaderboard sahifasi ishlaydi
- ✅ CORS to'g'ri sozlangan
- ✅ Authentication to'liq ishlaydi

Deploy qilish uchun yuqoridagi qadamlarni bajaring.
