FROM php:8.2-fpm

WORKDIR /var/www/html

# Install system dependencies
RUN apt-get update && apt-get install -y --no-install-recommends \
    git \
    curl \
    zip \
    unzip \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libwebp-dev \
    libonig-dev \
    libzip-dev \
    libxml2-dev \
    libssl-dev \
    libpq-dev \
    pkg-config \
    postgresql-client

# Install PHP extensions
RUN docker-php-ext-configure gd \
        --with-jpeg \
        --with-freetype \
        --with-webp \
    && docker-php-ext-install -j$(nproc) \
        gd \
        pdo_pgsql \
        mbstring \
        exif \
        pcntl \
        bcmath \
        soap \
        zip \
        sockets

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copy app files
COPY . .

# give permissions
RUN chown -R www-data:www-data bootstrap/cache storage \
    && chmod -R 755 bootstrap/cache storage

# Install PHP deps (ignore dev)
RUN composer install --optimize-autoloader

# 1. Copy setup command
COPY command/setup.sh /usr/local/bin/
# 2. Make it executable
RUN chmod +x /usr/local/bin/setup.sh

# 3. Define the STARTING point
ENTRYPOINT ["setup.sh"]

#for local
EXPOSE 8000
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8000"]

# Start PHP-FPM (production)
#EXPOSE 9000
#CMD ["php-fpm"]
