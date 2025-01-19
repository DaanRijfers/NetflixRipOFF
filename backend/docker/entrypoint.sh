#!/bin/bash

set -e

# Wait for the database to be operational
echo "Waiting for database to be ready at $DB_HOST:$DB_PORT..."
until nc -z "$DB_HOST" "$DB_PORT"; do
    echo "Database is unavailable - retrying..."
    sleep 1
done
echo "Database is ready!"

echo "Starting Laravel setup..."

# Install Composer if not already installed
if ! command -v composer &> /dev/null; then
    echo "Composer not found. Installing Composer..."
    curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer || { echo "Composer installation failed! Exiting."; exit 1; }
    echo "Composer installed successfully."
fi

# Install Composer dependencies
echo "Installing Composer dependencies..."
composer install --no-interaction --prefer-dist --optimize-autoloader || { echo "Composer install failed! Exiting."; exit 1; }
echo "Composer dependencies installed."

# Define the path to the root .env file (relative to where docker-compose.yml is mounted)
ROOT_ENV_FILE=/var/www/.env

# Check if the root .env file exists
if [ ! -f "$ROOT_ENV_FILE" ]; then
    echo "Root .env file not found at $ROOT_ENV_FILE!"
    ls -la /var/www  # Debug: List files in /var/www to confirm the volume
    exit 1
fi

# Load environment variables from the root .env file
echo "Loading environment variables from $ROOT_ENV_FILE..."
export $(grep -v '^#' "$ROOT_ENV_FILE" | xargs)

# Debug: Log the loaded variables
echo "Loaded environment variables:"
echo "DB_HOST: $DB_HOST"
echo "DB_PORT: $DB_PORT"

# Clean up DB_PORT to contain only digits
DB_PORT_CLEAN=$(echo "$DB_PORT" | tr -d -c '0-9')
export DB_PORT=$DB_PORT_CLEAN

# Generate Laravel .env file
if [ -f /var/www/html/.envExample ]; then
    echo "Generating Laravel .env file from .envExample..."
    envsubst < /var/www/html/.envExample > /var/www/html/.env
    chmod 644 /var/www/html/.env
    echo "Generated Laravel .env file:"
    cat /var/www/html/.env
else
    echo "Laravel .envExample file not found!"
    exit 1
fi

# Fix permissions for the .env file
chown www-data:www-data /var/www/html/.env
chmod 644 /var/www/html/.env

# Step 2: Ensure APP_KEY exists and is not empty
if ! grep -q "^APP_KEY=.*" /var/www/html/.env || grep -q "^APP_KEY=$" /var/www/html/.env; then
    echo "APP_KEY is missing or empty. Generating a new application key..."
    php artisan key:generate --force || { echo "Key generation failed! Exiting."; exit 1; }
    echo "Application key generated successfully."
else
    echo "APP_KEY already exists and is not empty."
fi

# Clear and cache configuration
echo "Clearing and caching configuration..."
php artisan config:clear && php artisan config:cache || { echo "Configuration caching failed! Exiting."; exit 1; }
composer dump-autoload
echo "Configuration cleared and cached."

# Load environment variables from the root .env file with debug logging
if [ -f "$ROOT_ENV_FILE" ]; then
    echo "Loading environment variables from $ROOT_ENV_FILE"
    set -a
    source <(grep -v '^#' "$ROOT_ENV_FILE" | sed 's/\r$//') || { echo "Failed to load environment variables!"; exit 1; }
    set +a
else
    echo "Root .env file not found!"
    exit 1
fi

# Debugging: Print the SEEDING variable to check if it's loaded
echo "SEEDING value: $SEEDING"

# Database setup
echo "Running database migrations..."
php artisan migrate --force || { echo "Migrations failed! Exiting."; exit 1; }

# Database seeding
if [ "$SEEDING" = "true" ]; then
    echo "SEEDING is enabled. Running database seeders..."
else
    echo "SEEDING is disabled. Only seeding procedures and views"
fi
php artisan db:seed --force || { echo "Seeding failed! Exiting."; exit 1; }


echo "Revoking database permissions..."
php artisan db:seed --class=GrantRevokePermissionsSeeder 

echo "Database setup completed."

# Start the Laravel development server
echo "Starting Laravel server..."
php artisan serve --host=0.0.0.0 --port=8000