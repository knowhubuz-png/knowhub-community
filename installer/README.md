# KnowHub Community - Installation Wizard

Professional Envato-style installation wizard for KnowHub Community platform.

## Features

- **Beautiful UI**: Modern, professional interface inspired by Envato products
- **Step-by-step Installation**: Guided installation process with 5 easy steps
- **Automatic Setup**: Automated database migration, optimization, and configuration
- **Requirement Checking**: Validates PHP version, extensions, and permissions
- **Real-time Progress**: Live feedback during installation process
- **Error Handling**: Clear error messages and recovery options
- **Security**: Token-based CSRF protection and secure configuration

## Installation Steps

### 1. Upload Files

Upload the entire application to your server:
- For cPanel: Extract files to `public_html` or subdirectory
- For VPS/Dedicated: Extract to your web root (e.g., `/var/www/html`)

### 2. Set Permissions

Ensure these folders are writable (755 or 777):
```bash
chmod -R 755 storage/
chmod -R 755 bootstrap/cache/
chmod -R 777 storage/framework/
chmod -R 777 storage/logs/
```

### 3. Install Dependencies

If Composer is available:
```bash
composer install --no-dev --optimize-autoloader
```

If not, upload the `vendor` folder from your local machine.

### 4. Run Installer

Navigate to: `https://yourdomain.com/installer/`

Follow the on-screen instructions:

#### Step 1: Welcome
- Review the installation features
- Click "Get Started"

#### Step 2: Requirements Check
- PHP version (8.2+)
- Required PHP extensions
- Directory permissions
- Fix any issues before continuing

#### Step 3: Database Configuration
- Enter your database credentials:
  - Database Host (usually `localhost`)
  - Database Port (usually `3306`)
  - Database Name
  - Database Username
  - Database Password
- Test connection before continuing
- Database will be created automatically if it doesn't exist

#### Step 4: Admin Account
- Enter administrator details:
  - Full Name
  - Username
  - Email Address
  - Password (minimum 8 characters)

#### Step 5: Finalization
- Watch the automated process:
  - Database migrations
  - Admin account creation
  - Application optimization
  - Final setup
- Click "Visit Your Website" when complete

### 5. Post-Installation

After successful installation:

1. **Delete Installer Folder** (Important for security):
   ```bash
   rm -rf installer/
   ```

2. **Configure Environment** (.env file):
   ```env
   APP_ENV=production
   APP_DEBUG=false
   APP_URL=https://yourdomain.com
   ```

3. **Set up Mail** (optional):
   ```env
   MAIL_MAILER=smtp
   MAIL_HOST=your-smtp-host
   MAIL_PORT=587
   MAIL_USERNAME=your-email
   MAIL_PASSWORD=your-password
   ```

4. **Configure OAuth** (optional):
   ```env
   GOOGLE_CLIENT_ID=your-google-client-id
   GOOGLE_CLIENT_SECRET=your-google-client-secret
   GITHUB_CLIENT_ID=your-github-client-id
   GITHUB_CLIENT_SECRET=your-github-client-secret
   ```

5. **Set up Cron Job** (for scheduled tasks):
   ```bash
   * * * * * cd /path-to-your-project && php artisan schedule:run >> /dev/null 2>&1
   ```

## Requirements

### Server Requirements
- PHP 8.2 or higher
- MySQL 5.7+ or MariaDB 10.3+
- Composer (or pre-installed vendor folder)

### PHP Extensions
- mysqli
- pdo_mysql
- openssl
- mbstring
- tokenizer
- xml
- ctype
- json
- bcmath
- curl
- zip
- fileinfo
- gd

### File Permissions
- `storage/` - Writable (755 or 777)
- `bootstrap/cache/` - Writable (755 or 777)

## Troubleshooting

### 500 Internal Server Error
1. Check PHP version (must be 8.2+)
2. Verify file permissions
3. Check error logs: `storage/logs/laravel.log`
4. Ensure `.env` file exists

### Database Connection Failed
1. Verify database credentials
2. Check if MySQL service is running
3. Ensure database user has proper privileges
4. Try `127.0.0.1` instead of `localhost`

### Composer Autoload Error
```bash
composer dump-autoload --optimize
```

### Missing Vendor Folder
If Composer is not available:
1. Run `composer install` on local machine
2. Upload the generated `vendor` folder to server

### Permission Denied
```bash
chmod -R 755 storage/
chmod -R 755 bootstrap/cache/
```

## Security

After installation:

1. ✅ Delete installer folder
2. ✅ Set `APP_DEBUG=false` in .env
3. ✅ Use strong database passwords
4. ✅ Enable HTTPS/SSL
5. ✅ Keep software updated
6. ✅ Regular backups

## Manual Installation

If installer fails, you can install manually:

1. Create `.env` file from `.env.example`
2. Generate app key: `php artisan key:generate`
3. Run migrations: `php artisan migrate --seed`
4. Create admin user via tinker:
   ```bash
   php artisan tinker
   ```
   ```php
   User::create([
       'name' => 'Admin',
       'username' => 'admin',
       'email' => 'admin@example.com',
       'password' => bcrypt('password'),
       'is_admin' => true,
       'email_verified_at' => now()
   ]);
   ```
5. Optimize: `php artisan optimize`

## Support

For issues or questions:
- 📧 Email: support@knowhub.uz
- 💬 Telegram: @knowhub_support
- 🐙 GitHub: https://github.com/knowhub-dev/knowhub-community

## License

MIT License - See LICENSE file for details
