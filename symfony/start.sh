#!/usr/bin/env bash
set -euo pipefail

# Dossiers runtime (uploads, etc.)
mkdir -p /app/public/media
chown -R www-data:www-data /app/public/media
chmod -R 775 /app/public/media

# Démarre PHP-FPM (daemon), puis Nginx au premier plan
php-fpm -D
exec nginx -g "daemon off;"
