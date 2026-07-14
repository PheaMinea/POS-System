# ================================
# Stage 1: Build Frontend
# ================================
FROM node:22-alpine AS frontend

WORKDIR /app

COPY package*.json ./

RUN npm install

COPY . .

RUN npm run build


# ================================
# Stage 2: Laravel PHP
# ================================
FROM php:8.4-cli

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    curl \
    unzip \
    zip \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libzip-dev \
    default-mysql-client \
    && docker-php-ext-install \
    pdo_mysql \
    mbstring \
    exif \
    pcntl \
    bcmath \
    gd \
    zip \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# ================================
# Composer
# ================================
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# ================================
# Laravel App
# ================================
WORKDIR /var/www/html

COPY . .

# ================================
# Install Laravel Dependencies
# ================================
RUN composer install \
    --no-dev \
    --optimize-autoloader \
    --no-interaction \
    --no-scripts

# ================================
# Copy Vite Build
# ================================
COPY --from=frontend /app/public/build /var/www/html/public/build

# ================================
# Laravel Directories
# ================================
RUN mkdir -p \
    storage/framework/cache/data \
    storage/framework/sessions \
    storage/framework/views \
    storage/logs \
    bootstrap/cache

# ================================
# Permissions
# ================================
RUN chown -R www-data:www-data \
    /var/www/html/storage \
    /var/www/html/bootstrap/cache \
    && chmod -R 775 \
    /var/www/html/storage \
    /var/www/html/bootstrap/cache

# ================================
# Railway Port
# ================================
EXPOSE 8080

# ================================
# Start Laravel
# ================================
CMD ["sh", "-c", "php artisan optimize:clear && php artisan serve --host=0.0.0.0 --port=${PORT:-8080}"]