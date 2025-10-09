# 🚀 KnowHub Community - VPS Deployment Guide

## ✅ Tayyorgarlik

### Amalga oshirilgan yaxshilanishlar:

1. **✨ Takomillashtirilgan Editor**
   - Markdown + Rich Text kombinatsiyasi
   - Live preview (edit/preview/split modes)
   - Toolbar bilan oson formatlash
   - Keyboard shortcuts (Ctrl+B, Ctrl+I)
   - Character/word count
   - Markdown cheat sheet

2. **🔧 Installer**
   - Envato-style professional installer
   - 5 bosqichli avtomatik o'rnatish
   - Server requirements check
   - Database auto-configuration
   - Real-time progress tracking

3. **📝 Documentation**
   - INSTALLATION_GUIDE.md - to'liq qo'llanma
   - QUICK_START.md - 5 daqiqalik start
   - Installer documentation

4. **🐛 Bug Fixes**
   - Frontend build xatolari tuzatildi
   - Backend duplicate files o'chirildi
   - .env.example to'liq konfiguratsiya

---

## 🖥️ VPS ga Deploy Qilish

### 1. Server Talablari

```bash
# Ubuntu 20.04+ / 22.04 tavsiya etiladi
- 2GB+ RAM
- 2+ CPU cores
- 20GB+ disk space
- Root yoki sudo access
```

### 2. Server Sozlash

```bash
# Serverga SSH orqali kirish
ssh root@your-server-ip

# System update
sudo apt update && sudo apt upgrade -y

# Install required packages
sudo apt install -y nginx mysql-server php8.2-fpm php8.2-mysql php8.2-xml \
php8.2-curl php8.2-zip php8.2-gd php8.2-mbstring php8.2-bcmath \
php8.2-tokenizer php8.2-cli git curl unzip supervisor certbot \
python3-certbot-nginx redis-server

# Install Composer
curl -sS https://getcomposer.org/installer | php
sudo mv composer.phar /usr/local/bin/composer

# Install Node.js  18+
curl -fsSL https://deb.nodesource.com/setup_18.x | sudo -E bash -
sudo apt install -y nodejs

# Start services
sudo systemctl start mysql nginx redis-server
sudo systemctl enable mysql nginx redis-server
```

### 3. MySQL Sozlash

```bash
# MySQL secure installation
sudo mysql_secure_installation

# Database yaratish
sudo mysql -u root -p
```

```sql
CREATE DATABASE knowhub_community CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER 'knowhub'@'localhost' IDENTIFIED BY 'YOUR_STRONG_PASSWORD';
GRANT ALL PRIVILEGES ON knowhub_community.* TO 'knowhub'@'localhost';
FLUSH PRIVILEGES;
EXIT;
```

### 4. Loyihani Deploy Qilish

```bash
# Project directory yaratish
sudo mkdir -p /var/www/knowhub
cd /var/www/knowhub

# Git clone (yoki FTP orqali yuklash)
git clone https://github.com/your-username/knowhub-community.git .

# File ownership
sudo chown -R www-data:www-data /var/www/knowhub
sudo chmod -R 755 /var/www/knowhub
sudo chmod -R 775 storage bootstrap/cache

# Composer dependencies
composer install --no-dev --optimize-autoloader

# Environment setup
cp .env.example .env
php artisan key:generate

# Edit .env file
nano .env
```

**.env Configuration:**
```env
APP_NAME="KnowHub Community"
APP_ENV=production
APP_DEBUG=false
APP_URL=https://yourdomain.com

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=knowhub_community
DB_USERNAME=knowhub
DB_PASSWORD=YOUR_STRONG_PASSWORD

CACHE_DRIVER=redis
SESSION_DRIVER=redis
QUEUE_CONNECTION=redis

REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379

MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your-email@gmail.com
MAIL_PASSWORD=your-app-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@yourdomain.com
```

```bash
# Run migrations
php artisan migrate --seed

# Storage link
php artisan storage:link

# Cache optimization
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan optimize
```

### 5. Nginx Configuration

```bash
sudo nano /etc/nginx/sites-available/knowhub
```

```nginx
server {
    listen 80;
    server_name yourdomain.com www.yourdomain.com;
    root /var/www/knowhub/public;

    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-XSS-Protection "1; mode=block";
    add_header X-Content-Type-Options "nosniff";

    index index.php;

    charset utf-8;

    # Gzip compression
    gzip on;
    gzip_vary on;
    gzip_proxied any;
    gzip_comp_level 6;
    gzip_types text/plain text/css text/xml text/javascript application/json application/javascript application/xml+rss application/rss+xml font/truetype font/opentype application/vnd.ms-fontobject image/svg+xml;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }

    error_page 404 /index.php;

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
        fastcgi_hide_header X-Powered-By;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }

    # Cache static assets
    location ~* \.(jpg|jpeg|png|gif|ico|css|js|svg|woff|woff2|ttf|eot)$ {
        expires 1y;
        add_header Cache-Control "public, immutable";
    }
}
```

```bash
# Enable site
sudo ln -s /etc/nginx/sites-available/knowhub /etc/nginx/sites-enabled/
sudo nginx -t
sudo systemctl reload nginx
```

### 6. SSL Certificate (Let's Encrypt)

```bash
# Install SSL
sudo certbot --nginx -d yourdomain.com -d www.yourdomain.com

# Auto-renewal
sudo certbot renew --dry-run
```

### 7. Queue Worker (Supervisor)

```bash
sudo nano /etc/supervisor/conf.d/knowhub-worker.conf
```

```ini
[program:knowhub-worker]
process_name=%(program_name)s_%(process_num)02d
command=php /var/www/knowhub/artisan queue:work redis --sleep=3 --tries=3 --max-time=3600
autostart=true
autorestart=true
stopasgroup=true
killasgroup=true
user=www-data
numprocs=2
redirect_stderr=true
stdout_logfile=/var/www/knowhub/storage/logs/worker.log
stopwaitsecs=3600
```

```bash
sudo supervisorctl reread
sudo supervisorctl update
sudo supervisorctl start knowhub-worker:*
```

### 8. Cron Job (Laravel Scheduler)

```bash
sudo crontab -e -u www-data
```

```cron
* * * * * cd /var/www/knowhub && php artisan schedule:run >> /dev/null 2>&1
```

### 9. Frontend Deploy (Next.js)

```bash
cd /var/www/knowhub/frontend

# Install dependencies
npm install

# Build
npm run build

# PM2 for process management
sudo npm install -g pm2
pm2 start npm --name "knowhub-frontend" -- start
pm2 save
pm2 startup
```

**Frontend Nginx Config** (`/etc/nginx/sites-available/knowhub-frontend`):

```nginx
server {
    listen 80;
    server_name app.yourdomain.com;

    location / {
        proxy_pass http://localhost:3000;
        proxy_http_version 1.1;
        proxy_set_header Upgrade $http_upgrade;
        proxy_set_header Connection 'upgrade';
        proxy_set_header Host $host;
        proxy_cache_bypass $http_upgrade;
    }
}
```

```bash
sudo ln -s /etc/nginx/sites-available/knowhub-frontend /etc/nginx/sites-enabled/
sudo certbot --nginx -d app.yourdomain.com
sudo systemctl reload nginx
```

---

## 🔒 Security Checklist

```bash
# Firewall setup
sudo ufw allow OpenSSH
sudo ufw allow 'Nginx Full'
sudo ufw enable

# Disable root login
sudo nano /etc/ssh/sshd_config
# Set: PermitRootLogin no
sudo systemctl restart ssh

# Fail2ban (optional)
sudo apt install fail2ban
sudo systemctl enable fail2ban
```

---

## 📊 Monitoring & Maintenance

### Log Files
```bash
# Laravel logs
tail -f /var/www/knowhub/storage/logs/laravel.log

# Nginx logs
tail -f /var/log/nginx/error.log
tail -f /var/log/nginx/access.log

# Queue worker logs
tail -f /var/www/knowhub/storage/logs/worker.log
```

### Performance Monitoring
```bash
# Server resources
htop

# MySQL
sudo mysqladmin -u root -p status
sudo mysqladmin -u root -p processlist

# Redis
redis-cli INFO
```

### Backup Script
```bash
#!/bin/bash
# /root/backup.sh

DATE=$(date +%Y%m%d_%H%M%S)
BACKUP_DIR="/backups"

# Database backup
mysqldump -u knowhub -p'PASSWORD' knowhub_community > $BACKUP_DIR/db_$DATE.sql

# Files backup
tar -czf $BACKUP_DIR/files_$DATE.tar.gz /var/www/knowhub/storage

# Keep only last 7 days
find $BACKUP_DIR -type f -mtime +7 -delete

echo "Backup completed: $DATE"
```

```bash
chmod +x /root/backup.sh

# Daily backup cron
sudo crontab -e
0 2 * * * /root/backup.sh >> /var/log/backup.log 2>&1
```

---

## 🚀 Post-Deployment

### 1. Create Admin User
```bash
php artisan tinker
```

```php
\App\Models\User::create([
    'name' => 'Administrator',
    'username' => 'admin',
    'email' => 'admin@yourdomain.com',
    'password' => bcrypt('secure_password'),
    'is_admin' => true,
    'email_verified_at' => now(),
]);
```

### 2. Test Everything
- ✅ Website loads: https://yourdomain.com
- ✅ SSL working (green padlock)
- ✅ Registration/Login
- ✅ Post creation
- ✅ Image uploads
- ✅ Email sending
- ✅ Queue processing

### 3. Performance Optimization
```bash
# Enable OPcache
sudo nano /etc/php/8.2/fpm/php.ini
```

```ini
opcache.enable=1
opcache.memory_consumption=256
opcache.interned_strings_buffer=16
opcache.max_accelerated_files=10000
opcache.validate_timestamps=0
opcache.revalidate_freq=0
```

```bash
sudo systemctl restart php8.2-fpm
```

---

## 📞 Support

Muammolar yuz bersa:
- Logs tekshiring: `storage/logs/laravel.log`
- GitHub Issues: https://github.com/your-repo/issues
- Email: support@yourdomain.com

---

**🎉 Deploy muvaffaqiyatli! Saytingiz ishlashga tayyor!**
