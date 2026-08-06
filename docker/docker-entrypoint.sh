#!/usr/bin/env bash
set -Eeuo pipefail

cd /workspace

if [[ ! -x /workspace/node_modules/.bin/nx || ! -x /workspace/node_modules/.bin/jest ]]; then
  npm ci --ignore-scripts
fi

if [[ ! -f /workspace/packages/turbocommons-php/vendor/autoload.php ]]; then
  composer install \
    --working-dir=/workspace/packages/turbocommons-php \
    --no-interaction \
    --no-progress \
    --prefer-dist
fi

stage_distribution() {
  local distribution_root=/workspace/dist
  local php_version
  local java_version
  local shell_version

  rm -rf "$distribution_root"
  mkdir -p \
    "$distribution_root/turbocommons-php" \
    "$distribution_root/turbocommons-ts" \
    "$distribution_root/turbocommons-java" \
    "$distribution_root/turbocommons-shell"

  php_version="$(node -p "require('./packages/turbocommons-php/version.json').version")"
  cp /workspace/packages/turbocommons-php/dist/turbocommons-php-"$php_version".phar \
    "$distribution_root/turbocommons-php/"

  find /workspace/packages/turbocommons-ts/dist/packages -maxdepth 1 -type f -name '*.tgz' \
    -exec cp {} "$distribution_root/turbocommons-ts/" \;

  java_version="$(sed -n 's/^version=//p' /workspace/packages/turbocommons-java/version.properties)"
  find /workspace/packages/turbocommons-java/build/libs -maxdepth 1 -type f -name '*.jar' \
    -exec cp {} "$distribution_root/turbocommons-java/" \;
  printf '%s\n' "$java_version" > "$distribution_root/turbocommons-java/VERSION"

  shell_version="$(node -p "require('./packages/turbocommons-shell/package.json').version")"
  tar -czf "$distribution_root/turbocommons-shell/turbocommons-shell-"$shell_version".tar.gz" \
    -C /workspace/packages/turbocommons-shell ubuntu win-powershell README.md package.json
}

case "${1:-ci}" in
  clean)
    rm -rf \
      /workspace/.nx \
      /workspace/dist \
      /workspace/packages/turbocommons-*/dist \
      /workspace/packages/turbocommons-*/target \
      /workspace/packages/turbocommons-*/build \
      /workspace/packages/turbocommons-*/bin
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
    npx nx run-many -t package --all --parallel=3
    stage_distribution
    ;;
  dist)
    npx nx run-many -t package --all --parallel=3
    stage_distribution
    ;;
  ci)
    npx nx run-many -t lint --all --parallel=3
    npx nx run-many -t test --all --parallel=3
    npx nx run-many -t package --all --parallel=3
    stage_distribution
    ;;
  shell|bash)
    exec bash
    ;;
  *)
    exec "$@"
    ;;
esac
