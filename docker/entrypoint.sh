!/bin/bash

# Run database migrations and seeders on the first run
if [ ! -f /var/www/html/.db-initialized ]; then
    echo "Initializing database..."
    php artisan migrate --force
    php artisan db:seed --force
    touch /var/www/html/.db-initialized
    echo "Database initialized."
    npm run build
fi

# Start cron, PHP-FPM, and Vite dev server
cron
php-fpm &
npm run dev
