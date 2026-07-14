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
# Stage 2: Laravel PHP + Apache
# ================================
FROM php:8.4-apache

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
# Fix Apache MPM
# ================================
RUN a2dismod mpm_event || true \
    && a2dismod mpm_worker || true \
    && a2enmod mpm_prefork \
    && a2enmod rewrite


# ================================
# Install Composer
# ================================
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer


# ================================
# Laravel App
# ================================
WORKDIR /var/www/html

COPY . .


# ================================
# Install PHP Dependencies
# ================================
RUN composer install \
    --no-dev \
    --optimize-autoloader \
    --no-interaction \
    --no-scripts


# ================================
# Copy Frontend Build
# ================================
COPY --from=frontend /app/public/build /var/www/html/public/build


# ================================
# Laravel Storage Directories
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
# Apache Document Root
# ================================
RUN sed -ri \
    's#DocumentRoot /var/www/html#DocumentRoot /var/www/html/public#g' \
    /etc/apache2/sites-available/000-default.conf


# ================================
# Laravel Apache Configuration
# ================================
RUN printf '<Directory /var/www/html/public>\n\
    Options FollowSymLinks\n\
    AllowOverride All\n\
    Require all granted\n\
</Directory>\n' \
    > /etc/apache2/conf-available/laravel.conf \
    && a2enconf laravel


# ================================
# Verify Apache Configuration
# ================================
RUN apache2ctl configtest


# ================================
# Railway Port
# ================================
EXPOSE 80


# ================================
# Start Apache
# ================================
CMD ["apache2-foreground"]