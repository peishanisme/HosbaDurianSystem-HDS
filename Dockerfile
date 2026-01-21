# ================================
# Stage 1: Frontend build (Vite)
# ================================

# Use Node.js image to build frontend assets
FROM node:20-alpine AS frontend

# Set working directory for frontend build
WORKDIR /app

# Copy project files required for frontend build
COPY . .

# Install frontend dependencies
RUN npm ci || npm install

# Build production frontend assets
RUN npm run build


# ================================
# Stage 2: Backend runtime (Laravel)
# ================================

# Use PHP CLI image to run the Laravel application
FROM php:8.3-cli

# Set working directory for Laravel backend
WORKDIR /var/www

# Install system libraries and PHP extensions required by Laravel
RUN apt-get update && apt-get install -y --no-install-recommends \
    git unzip libzip-dev \
    libpng-dev libjpeg-dev libfreetype6-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install pdo pdo_mysql zip gd \
    && rm -rf /var/lib/apt/lists/*

# Copy Composer binary
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Copy backend source code into container
COPY . .

# Copy compiled frontend assets from frontend stage
COPY --from=frontend /app/public/build ./public/build

# Install PHP dependencies
RUN composer install --no-interaction --no-dev --prefer-dist

# Prepare Laravel environment
RUN cp .env.example .env && php artisan key:generate

# Clear cached configuration
RUN php artisan config:clear

# Expose application port
EXPOSE 5000

# Start Laravel built-in server
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=5000"]