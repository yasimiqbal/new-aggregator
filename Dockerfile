# Use the official PHP 8.2 image with extensions and Composer installed
FROM php:8.2-fpm

# Set working directory inside the container
WORKDIR /var/www/html

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libjpeg62-turbo-dev \
    libfreetype6-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    cron \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Install PHP extensions
RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copy existing project files
COPY . .

# Install Laravel dependencies
RUN composer install --no-dev --optimize-autoloader

# Set proper permissions
RUN chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache \
    && chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# Ensure required Laravel directories have correct permissions
RUN mkdir -p /var/www/html/storage/logs /var/www/html/bootstrap/cache \
    && chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache \
    && find /var/www/html/storage /var/www/html/bootstrap/cache -type d -exec chmod 775 {} \; \
    && find /var/www/html/storage /var/www/html/bootstrap/cache -type f -exec chmod 664 {} \;

# Add the cron job \
RUN echo "* * * * * www-data /usr/local/bin/php /var/www/html/artisan schedule:run >> /var/log/cron.log 2>&1" > /etc/cron.d/schedule \
    && chmod 0644 /etc/cron.d/schedule \
    && crontab /etc/cron.d/schedule

# Create the cron log file
RUN touch /var/log/cron.log && chmod 0666 /var/log/cron.log

# Expose port 9000 for PHP-FPM
EXPOSE 9000

# Copy and set permissions for the entrypoint script
COPY ./docker-entrypoint.sh /usr/local/bin/docker-entrypoint.sh
RUN chmod +x /usr/local/bin/docker-entrypoint.sh

# Use the entrypoint script to start both services
ENTRYPOINT ["docker-entrypoint.sh"]
