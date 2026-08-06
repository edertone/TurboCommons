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

  php_version="$(node -p "require('./turbocommons-php/version.json').version")"
  cp /workspace/turbocommons-php/dist/turbocommons-php-"$php_version".phar \
    "$distribution_root/turbocommons-php/"

  find /workspace/turbocommons-ts/dist/packages -maxdepth 1 -type f -name '*.tgz' \
    -exec cp {} "$distribution_root/turbocommons-ts/" \;

  java_version="$(sed -n 's/^version=//p' /workspace/turbocommons-java/version.properties)"
  find /workspace/turbocommons-java/build/libs -maxdepth 1 -type f -name '*.jar' \
    -exec cp {} "$distribution_root/turbocommons-java/" \;
  printf '%s\n' "$java_version" > "$distribution_root/turbocommons-java/VERSION"

  shell_version="$(node -p "require('./turbocommons-shell/package.json').version")"
  tar -czf "$distribution_root/turbocommons-shell/turbocommons-shell-"$shell_version".tar.gz" \
    -C /workspace/turbocommons-shell ubuntu win-powershell README.md package.json
}

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
