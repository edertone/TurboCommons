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

case "${1:-ci}" in
  clean)
    rm -rf \
      /workspace/.nx \
      /workspace/dist \
      /workspace/turbocommons-*/dist \
      /workspace/turbocommons-*/target \
      /workspace/turbocommons-*/build \
      /workspace/turbocommons-*/bin
    ;;
  build)
    exec npx nx run-many -t build --all --parallel=3
    ;;
  test)
    exec npx nx run-many -t test --all --parallel=3
    ;;
  lint)
    exec npx nx run-many -t lint --all --parallel=3
    ;;
  package)
    exec npx nx run-many -t package --all --parallel=3
    ;;
  ci)
    npx nx run-many -t lint --all --parallel=3
    npx nx run-many -t test --all --parallel=3
    exec npx nx run-many -t package --all --parallel=3
    ;;
  shell|bash)
    exec bash
    ;;
  *)
    exec "$@"
    ;;
esac
