# Use an official PHP runtime as the base image
FROM php:8.3-fpm

# Install system dependencies
RUN apt-get update && apt-get install -y \
    cron \
    build-essential \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    locales \
    zip \
    jpegoptim optipng pngquant gifsicle \
    vim \
    unzip \
    git \
    curl \
    gnupg

# Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Install PHP extensions
RUN docker-php-ext-install pdo pdo_mysql gd exif pcntl bcmath opcache

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Install Node.js and npm
RUN curl -fsSL https://deb.nodesource.com/setup_18.x | bash - && \
    apt-get install -y nodejs

# Set working directory
WORKDIR /var/www/html

# Copy application contents to the container
COPY . /var/www/html

# Install npm dependencies
RUN npm install

# Set permissions for Laravel
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html/storage \
    && chmod -R 755 /var/www/html/bootstrap/cache

# Add the crontab file
COPY ./docker/crontab /etc/cron.d/laravel-cron

# Set cron job permissions and apply
RUN chmod 0644 /etc/cron.d/laravel-cron \
    && crontab /etc/cron.d/laravel-cron \
    && touch /var/log/cron.log

# Expose ports
EXPOSE 9000 5173

# Build assets for production
RUN npm run build

# Start cron, PHP-FPM, and Vite dev server
CMD ["sh", "-c", "cron && php-fpm & npm run dev"]
