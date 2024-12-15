FROM php:8.2-fpm

# Install required dependencies
RUN apt-get update && apt-get install -y \
    zip unzip curl git libzip-dev gettext-base netcat-openbsd \
    && docker-php-ext-install pdo_mysql zip

# Set the working directory
WORKDIR /var/www/html

# Copy application files
COPY . .

# Copy entrypoint script
COPY docker/entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh

# Set the entrypoint script
ENTRYPOINT ["/usr/local/bin/entrypoint.sh"]

# Expose the application port
EXPOSE 8000
