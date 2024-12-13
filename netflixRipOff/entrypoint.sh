#!/bin/sh

set -e

echo "Starting Laravel setup..."

# Step 1: Install Composer dependencies
echo "Installing Composer dependencies..."
composer install || { echo "Composer install failed! Exiting."; exit 1; }
echo "Composer dependencies installed."

# Step 2: Generate application key
echo "Generating application key..."
php artisan key:generate || { echo "Key generation failed! Exiting."; exit 1; }
echo "Application key generated."

# Step 3: Clear and cache configuration
echo "Clearing and caching configuration..."
php artisan config:clear && php artisan config:cache || { echo "Configuration caching failed! Exiting."; exit 1; }
echo "Configuration cleared and cached."

# Step 4: Run database migrations and seeders
echo "Running database migrations and seeders..."
php artisan migrate --force && php artisan db:seed || { echo "Migrations or seeding failed! Exiting."; exit 1; }
echo "Database setup completed."

# Step 5: Start Laravel development server
echo "Starting Laravel development server..."
php artisan serve --host=0.0.0.0 --port=8000
