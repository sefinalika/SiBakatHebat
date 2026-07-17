# Dockerfile untuk deploy Si Bakat Hebat (Laravel) ke Render (gratis).
# Server: PHP built-in server dengan beberapa worker (cukup untuk pilot).
# Database: PostgreSQL (disediakan gratis oleh Render) — Laravel mendukungnya.

FROM php:8.3-cli

# Dependensi sistem + ekstensi PHP yang dibutuhkan Laravel & PostgreSQL.
RUN apt-get update && apt-get install -y --no-install-recommends \
        git unzip libpq-dev libzip-dev libonig-dev \
    && docker-php-ext-install pdo_pgsql mbstring bcmath zip \
    && rm -rf /var/lib/apt/lists/*

# Opcache — mempercepat PHP di produksi secara signifikan.
RUN docker-php-ext-install opcache
RUN { \
        echo 'opcache.enable=1'; \
        echo 'opcache.memory_consumption=128'; \
        echo 'opcache.max_accelerated_files=10000'; \
        echo 'opcache.validate_timestamps=0'; \
    } > /usr/local/etc/php/conf.d/opcache.ini

# Composer.
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /app
COPY . /app

# Install dependency PHP (aset frontend sudah di-commit di public/build).
RUN composer install --no-dev --optimize-autoloader --no-interaction --no-scripts

# Beberapa worker agar bisa melayani beberapa permintaan sekaligus.
ENV PHP_CLI_SERVER_WORKERS=8

# Script start: migrasi + seed, jalankan queue worker (untuk email hasil tes)
# di latar belakang, lalu jalankan web server di foreground.
RUN printf '%s\n' \
    '#!/bin/sh' \
    'set -e' \
    'php artisan package:discover --ansi' \
    'php artisan migrate --force' \
    'php artisan db:seed --force' \
    'php artisan config:cache' \
    'php artisan route:cache' \
    'php artisan view:cache' \
    '# Queue worker: mengirim email hasil tes (render PDF + SMTP) di luar request.' \
    'php artisan queue:work --queue=default --sleep=3 --tries=3 --max-time=3600 &' \
    'exec php artisan serve --host=0.0.0.0 --port=${PORT:-10000}' \
    > /app/start.sh && chmod +x /app/start.sh

CMD ["/app/start.sh"]
