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
    gnupg # For Node.js

# Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Install PHP extensions
RUN docker-php-ext-install pdo pdo_mysql gd exif pcntl bcmath opcache

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Install Node.js and npm (using NodeSource for the latest Node.js version)
RUN curl -fsSL https://deb.nodesource.com/setup_18.x | bash - && \
    apt-get install -y nodejs

# Verify Node.js and npm installation
RUN node -v && npm -v

# Set working directory
WORKDIR /var/www/html

# Copy existing application directory contents to the container
COPY . /var/www/html

# Install npm dependencies
RUN npm install

# Set permissions for Laravel
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html/storage \
    && chmod -R 755 /var/www/html/bootstrap/cache

# Add the crontab file
COPY ./docker/crontab /etc/cron.d/laravel-cron

# Give execution rights to the cron job file
RUN chmod 0644 /etc/cron.d/laravel-cron

# Apply the cron job
RUN crontab /etc/cron.d/laravel-cron

# Create a log file for cron (optional)
RUN touch /var/log/cron.log

# Expose port 9000 for PHP-FPM
EXPOSE 9000

# Expose port 5173 for Vite (default port for development server)
EXPOSE 5173

# Run the scheduler and services in parallel
CMD ["sh", "-c", "cron && php-fpm & npm run dev"]
