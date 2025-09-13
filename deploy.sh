#!/bin/bash

# KnowHub Community Deployment Script
set -e

echo "🚀 Starting KnowHub Community deployment..."

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

# Configuration
DOMAIN="knowhub.uz"
DB_PASSWORD=$(openssl rand -base64 32)
APP_KEY=$(php artisan key:generate --show)

# Function to print colored output
print_status() {
    echo -e "${GREEN}[INFO]${NC} $1"
}

print_warning() {
    echo -e "${YELLOW}[WARNING]${NC} $1"
}

print_error() {
    echo -e "${RED}[ERROR]${NC} $1"
}

# Check if running as root
if [[ $EUID -eq 0 ]]; then
   print_error "This script should not be run as root for security reasons"
   exit 1
fi

# Check if Docker is installed
if ! command -v docker &> /dev/null; then
    print_error "Docker is not installed. Please install Docker first."
    exit 1
fi

# Check if Docker Compose is installed
if ! command -v docker-compose &> /dev/null; then
    print_error "Docker Compose is not installed. Please install Docker Compose first."
    exit 1
fi

# Create .env file if it doesn't exist
if [ ! -f .env ]; then
    print_status "Creating .env file..."
    cp .env.example .env
    
    # Update .env with generated values
    sed -i "s/APP_KEY=/APP_KEY=${APP_KEY}/" .env
    sed -i "s/DB_PASSWORD=/DB_PASSWORD=${DB_PASSWORD}/" .env
    sed -i "s/APP_URL=.*/APP_URL=https:\/\/${DOMAIN}/" .env
    
    print_warning "Please update the .env file with your specific configuration:"
    print_warning "- Database credentials"
    print_warning "- OAuth credentials (Google, GitHub)"
    print_warning "- OpenAI API key"
    print_warning "- Email configuration"
    print_warning "- AWS S3 credentials (if using)"
    
    read -p "Press Enter to continue after updating .env file..."
fi

# Create frontend .env file
if [ ! -f frontend/.env.local ]; then
    print_status "Creating frontend .env.local file..."
    cp frontend/.env.example frontend/.env.local
    sed -i "s/NEXT_PUBLIC_API_URL=.*/NEXT_PUBLIC_API_URL=https:\/\/api.${DOMAIN}\/api\/v1/" frontend/.env.local
    sed -i "s/NEXT_PUBLIC_APP_URL=.*/NEXT_PUBLIC_APP_URL=https:\/\/${DOMAIN}/" frontend/.env.local
fi

# Create SSL directory
print_status "Setting up SSL certificates..."
mkdir -p ssl

# Generate self-signed certificates for development (replace with real certificates for production)
if [ ! -f ssl/cert.pem ] || [ ! -f ssl/key.pem ]; then
    print_warning "Generating self-signed SSL certificates for development..."
    print_warning "For production, replace these with real SSL certificates!"
    
    openssl req -x509 -nodes -days 365 -newkey rsa:2048 \
        -keyout ssl/key.pem \
        -out ssl/cert.pem \
        -subj "/C=UZ/ST=Tashkent/L=Tashkent/O=KnowHub/CN=${DOMAIN}"
fi

# Build and start containers
print_status "Building Docker containers..."
docker-compose build --no-cache

print_status "Starting containers..."
docker-compose up -d

# Wait for database to be ready
print_status "Waiting for database to be ready..."
sleep 30

# Run database migrations
print_status "Running database migrations..."
docker-compose exec app php artisan migrate --force

# Seed the database
print_status "Seeding database..."
docker-compose exec app php artisan db:seed --force

# Clear and cache configuration
print_status "Optimizing application..."
docker-compose exec app php artisan config:cache
docker-compose exec app php artisan route:cache
docker-compose exec app php artisan view:cache

# Set proper permissions
print_status "Setting file permissions..."
docker-compose exec app chown -R www-data:www-data /var/www/html/storage
docker-compose exec app chown -R www-data:www-data /var/www/html/bootstrap/cache

# Create symbolic link for storage
docker-compose exec app php artisan storage:link

print_status "Deployment completed successfully! 🎉"
print_status ""
print_status "Your KnowHub Community is now running at:"
print_status "🌐 Frontend: https://${DOMAIN}"
print_status "🔧 API: https://api.${DOMAIN}"
print_status ""
print_status "Next steps:"
print_status "1. Update your DNS records to point to this server"
print_status "2. Replace self-signed certificates with real SSL certificates"
print_status "3. Configure your OAuth applications with the correct redirect URLs"
print_status "4. Set up monitoring and backups"
print_status "5. Configure email settings for notifications"
print_status ""
print_status "To view logs: docker-compose logs -f"
print_status "To stop: docker-compose down"
print_status "To restart: docker-compose restart"
print_status ""
print_warning "Database password: ${DB_PASSWORD}"
print_warning "Please save this password securely!"