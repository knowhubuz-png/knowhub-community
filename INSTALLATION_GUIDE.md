# 🚀 KnowHub Community - Complete Installation Guide

## Installation Methods

Choose one of the following installation methods:

### Method 1: Web Installer (Recommended for Shared Hosting) ⭐

1. **Upload Files**
   - Upload all files to your server
   - Extract to `public_html` or your web root

2. **Set Permissions**
   ```bash
   chmod -R 755 storage/
   chmod -R 755 bootstrap/cache/
   ```

3. **Install Dependencies**
   - If Composer available: `composer install --no-dev`
   - If not: Upload `vendor` folder from local machine

4. **Run Web Installer**
   - Navigate to: `https://yourdomain.com/installer/`
   - Follow 5 simple steps:
     1. Welcome & Requirements Check
     2. Server Requirements Verification
     3. Database Configuration
     4. Admin Account Creation
     5. Automatic Setup & Optimization

5. **Complete**
   - Delete installer folder: `rm -rf installer/`
   - Your site is ready!

---

### Method 2: Manual Installation (VPS/Dedicated Server)

#### Prerequisites
- PHP 8.2+
- Composer
- MySQL/MariaDB
- Node.js 18+
- Git

#### Backend Setup

1. **Clone Repository**
   ```bash
   git clone https://github.com/yourusername/knowhub-community.git
   cd knowhub-community
   ```

2. **Install Dependencies**
   ```bash
   composer install
   ```

3. **Environment Configuration**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

4. **Configure Database**
   Edit `.env` file:
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=knowhub_community
   DB_USERNAME=root
   DB_PASSWORD=your_password
   ```

5. **Run Migrations**
   ```bash
   php artisan migrate --seed
   ```

6. **Storage Link**
   ```bash
   php artisan storage:link
   ```

7. **Optimize Application**
   ```bash
   php artisan config:cache
   php artisan route:cache
   php artisan view:cache
   ```

#### Frontend Setup

1. **Navigate to Frontend**
   ```bash
   cd frontend
   ```

2. **Install Dependencies**
   ```bash
   npm install
   ```

3. **Configure Environment**
   ```bash
   cp .env.example .env.local
   ```

   Edit `.env.local`:
   ```env
   NEXT_PUBLIC_API_URL=http://localhost:8000/api/v1
   ```

4. **Build Frontend**
   ```bash
   npm run build
   ```

#### Running Development Servers

```bash
# Terminal 1: Laravel Backend
php artisan serve

# Terminal 2: Next.js Frontend
cd frontend && npm run dev

# Terminal 3: Queue Worker
php artisan queue:work
```

---

### Method 3: Docker Installation (Production)

1. **Clone Repository**
   ```bash
   git clone https://github.com/yourusername/knowhub-community.git
   cd knowhub-community
   ```

2. **Configure Environment**
   ```bash
   cp .env.example .env
   # Edit .env with your production settings
   ```

3. **Run Docker Compose**
   ```bash
   docker-compose up -d
   ```

4. **Run Migrations**
   ```bash
   docker-compose exec app php artisan migrate --seed
   ```

5. **Access Application**
   - Backend: http://localhost:8000
   - Frontend: http://localhost:3000

---

## Server Requirements

### PHP Requirements
- PHP 8.2 or higher
- BCMath PHP Extension
- Ctype PHP Extension
- Fileinfo PHP Extension
- JSON PHP Extension
- Mbstring PHP Extension
- OpenSSL PHP Extension
- PDO PHP Extension
- Tokenizer PHP Extension
- XML PHP Extension
- cURL PHP Extension
- GD PHP Extension
- ZIP PHP Extension

### Database
- MySQL 5.7+ or MariaDB 10.3+
- PostgreSQL 10+ (alternative)

### Web Server
- Apache with mod_rewrite
- Nginx
- Recommended: 2GB+ RAM, 2+ CPU cores

---

## Configuration

### Database Configuration

Create database:
```sql
CREATE DATABASE knowhub_community CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER 'knowhub_user'@'localhost' IDENTIFIED BY 'strong_password';
GRANT ALL PRIVILEGES ON knowhub_community.* TO 'knowhub_user'@'localhost';
FLUSH PRIVILEGES;
```

### Email Configuration

For Gmail SMTP:
```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your-email@gmail.com
MAIL_PASSWORD=your-app-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@yourdomain.com
```

### OAuth Setup (Optional)

#### Google OAuth
1. Go to [Google Cloud Console](https://console.cloud.google.com/)
2. Create project and enable Google+ API
3. Create OAuth credentials
4. Add to `.env`:
   ```env
   GOOGLE_CLIENT_ID=your-client-id
   GOOGLE_CLIENT_SECRET=your-client-secret
   ```

#### GitHub OAuth
1. Go to [GitHub Developer Settings](https://github.com/settings/developers)
2. Create OAuth App
3. Add to `.env`:
   ```env
   GITHUB_CLIENT_ID=your-client-id
   GITHUB_CLIENT_SECRET=your-client-secret
   ```

---

## Production Deployment

### Shared Hosting (cPanel)

1. **File Structure**
   ```
   /home/username/
   ├── public_html/           # Laravel public folder contents
   │   ├── index.php
   │   ├── .htaccess
   │   └── assets/
   ├── app/                   # Laravel app folder
   ├── config/                # Config files
   ├── database/              # Migrations
   └── vendor/                # Dependencies
   ```

2. **Modify index.php**
   Update paths in `public_html/index.php`:
   ```php
   require __DIR__.'/../vendor/autoload.php';
   $app = require_once __DIR__.'/../bootstrap/app.php';
   ```

3. **Setup Cron**
   Add to cPanel Cron Jobs:
   ```bash
   * * * * * cd /home/username && php artisan schedule:run >> /dev/null 2>&1
   ```

### VPS/Cloud Deployment

1. **Install Dependencies**
   ```bash
   sudo apt update
   sudo apt install php8.2 php8.2-fpm php8.2-mysql php8.2-xml php8.2-curl php8.2-zip php8.2-gd composer nginx mysql-server
   ```

2. **Configure Nginx**
   Create `/etc/nginx/sites-available/knowhub`:
   ```nginx
   server {
       listen 80;
       server_name yourdomain.com;
       root /var/www/knowhub/public;

       add_header X-Frame-Options "SAMEORIGIN";
       add_header X-Content-Type-Options "nosniff";

       index index.php;

       charset utf-8;

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
       }

       location ~ /\.(?!well-known).* {
           deny all;
       }
   }
   ```

3. **Enable Site**
   ```bash
   sudo ln -s /etc/nginx/sites-available/knowhub /etc/nginx/sites-enabled/
   sudo nginx -t
   sudo systemctl reload nginx
   ```

4. **SSL Certificate**
   ```bash
   sudo apt install certbot python3-certbot-nginx
   sudo certbot --nginx -d yourdomain.com
   ```

5. **Setup Supervisor** (for queue workers)
   Create `/etc/supervisor/conf.d/knowhub-worker.conf`:
   ```ini
   [program:knowhub-worker]
   process_name=%(program_name)s_%(process_num)02d
   command=php /var/www/knowhub/artisan queue:work --sleep=3 --tries=3 --max-time=3600
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

   Start supervisor:
   ```bash
   sudo supervisorctl reread
   sudo supervisorctl update
   sudo supervisorctl start knowhub-worker:*
   ```

---

## Post-Installation

### Create Admin User (if not using installer)

```bash
php artisan tinker
```

```php
\App\Models\User::create([
    'name' => 'Administrator',
    'username' => 'admin',
    'email' => 'admin@example.com',
    'password' => bcrypt('password123'),
    'is_admin' => true,
    'email_verified_at' => now(),
]);
```

### Optimize for Production

```bash
composer install --optimize-autoloader --no-dev
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan optimize
```

### Setup Backup

Install Laravel Backup:
```bash
composer require spatie/laravel-backup
php artisan vendor:publish --provider="Spatie\Backup\BackupServiceProvider"
```

Configure cron:
```bash
0 2 * * * cd /var/www/knowhub && php artisan backup:run >> /dev/null 2>&1
```

---

## Security Checklist

- [ ] Change default admin password
- [ ] Set `APP_DEBUG=false` in production
- [ ] Set `APP_ENV=production`
- [ ] Use strong database password
- [ ] Enable HTTPS/SSL
- [ ] Configure firewall
- [ ] Set proper file permissions (755 for folders, 644 for files)
- [ ] Hide `.env` file from web access
- [ ] Enable CSRF protection
- [ ] Configure rate limiting
- [ ] Regular backups
- [ ] Keep software updated

---

## Troubleshooting

### Common Issues

#### 500 Internal Server Error
```bash
# Check logs
tail -f storage/logs/laravel.log

# Clear cache
php artisan cache:clear
php artisan config:clear
php artisan view:clear

# Check permissions
chmod -R 755 storage bootstrap/cache
```

#### Database Connection Error
- Verify database credentials in `.env`
- Check if MySQL is running: `sudo systemctl status mysql`
- Test connection: `mysql -u username -p database_name`

#### Composer Memory Error
```bash
php -d memory_limit=-1 /usr/local/bin/composer install
```

#### Queue Not Processing
```bash
# Check queue worker
php artisan queue:work --verbose

# Restart queue
php artisan queue:restart
```

---

## Maintenance

### Update Application
```bash
git pull origin main
composer install --no-dev
php artisan migrate --force
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### Clear All Caches
```bash
php artisan optimize:clear
```

### Database Backup
```bash
mysqldump -u username -p knowhub_community > backup.sql
```

### Monitoring
- Check logs: `storage/logs/laravel.log`
- Monitor disk space
- Monitor database size
- Track application performance

---

## Support

- 📧 Email: support@knowhub.uz
- 💬 Telegram: @knowhub_community
- 🐙 GitHub: https://github.com/knowhub-dev/knowhub-community
- 📖 Documentation: https://docs.knowhub.uz

---

## License

MIT License - See LICENSE file for details
