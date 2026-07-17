#!/usr/bin/env bash
# Setup otomatis Si Bakat Hebat di GitHub Codespaces (pakai SQLite, tanpa server DB terpisah).
# Best-effort: jangan pakai `set -e` agar container tetap bisa dipakai walau ada 1 langkah gagal.

echo "=== [1/5] Pastikan driver SQLite ada ==="
if ! php -m | grep -qi pdo_sqlite; then
    sudo docker-php-ext-install pdo_sqlite || echo "!! gagal install pdo_sqlite"
fi

echo "=== [2/5] composer install ==="
composer install --no-interaction --prefer-dist

echo "=== [3/5] Siapkan .env ==="
[ -f .env ] || cp .env.example .env
touch database/database.sqlite
sed -i 's/^DB_CONNECTION=.*/DB_CONNECTION=sqlite/' .env
sed -i "s#^DB_DATABASE=.*#DB_DATABASE=$(pwd)/database/database.sqlite#" .env
sed -i 's/^APP_ENV=.*/APP_ENV=local/' .env
sed -i 's/^APP_DEBUG=.*/APP_DEBUG=true/' .env
if [ -n "$CODESPACE_NAME" ]; then
    sed -i "s#^APP_URL=.*#APP_URL=https://${CODESPACE_NAME}-8000.${GITHUB_CODESPACES_PORT_FORWARDING_DOMAIN}#" .env
fi

echo "=== [4/5] Generate key ==="
php artisan key:generate --force

echo "=== [5/5] Migrasi + seed database ==="
php artisan migrate --seed --force

echo ""
echo "=================================================="
echo " SETUP SELESAI. Server akan berjalan OTOMATIS."
echo " Buka tab PORTS -> klik URL port 8000 (ikon globe)."
echo " Kalau server belum jalan, ketik di Terminal:"
echo "   php artisan serve --host=0.0.0.0 --port=8000"
echo "=================================================="
