#!/usr/bin/env sh
set -eu

mkdir -p bootstrap/cache storage/framework/cache storage/framework/sessions storage/framework/views storage/logs
chmod -R u+rwX,go+rwX bootstrap/cache storage || true

if [ ! -f vendor/autoload.php ]; then
    echo "vendor/autoload.php is missing."
    echo "Run: docker compose -f docker-compose.php74.yml run --rm app composer install"
fi

exec "$@"
