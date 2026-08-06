#!/usr/bin/env bash
set -Eeuo pipefail

cd /workspace

if [[ ! -x /workspace/node_modules/.bin/nx ]]; then
  npm ci --ignore-scripts
fi

if [[ ! -f /workspace/turbocommons-php/vendor/autoload.php ]]; then
  composer install \
    --working-dir=/workspace/turbocommons-php \
    --no-interaction \
    --no-progress \
    --prefer-dist
fi

exec "$@"
