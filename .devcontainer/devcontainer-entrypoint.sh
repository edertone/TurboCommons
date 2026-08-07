#!/usr/bin/env bash
set -Eeuo pipefail

workspace_root="${TURBOCOMMONS_WORKSPACE:-/workspace/turbocommons}"
nx_root="${TURBOCOMMONS_NX_ROOT:-/opt/turbocommons}"
ts_root="${TURBOCOMMONS_TS_ROOT:-/opt/turbocommons-ts}"

cd "$workspace_root"

dependency_stamp() {
  sha256sum "$@" | sha256sum | awk '{print $1}'
}

ensure_dependencies() {
  local nx_modules="$nx_root/node_modules"
  local ts_modules="$ts_root/node_modules"
  local nx_marker="$nx_modules/.turbocommons-dependencies"
  local ts_marker="$ts_modules/.turbocommons-dependencies"
  local composer_marker="$workspace_root/packages/turbocommons-php/vendor/.turbocommons-dependencies"
  local nx_stamp
  local ts_stamp
  local composer_stamp

  nx_stamp="$(dependency_stamp package.json package-lock.json)"
  if [[ ! -x "$nx_modules/.bin/nx" || ! -f "$nx_marker" || "$(cat "$nx_marker")" != "$nx_stamp" ]]; then
    echo 'Installing Nx dependencies...'
    cp package.json package-lock.json "$nx_root/"
    npm ci --prefix "$nx_root" --ignore-scripts
    printf '%s\n' "$nx_stamp" > "$nx_marker"
  fi

  ts_stamp="$(dependency_stamp packages/turbocommons-ts/package.json packages/turbocommons-ts/package-lock.json)"
  if [[ ! -x "$ts_modules/.bin/webpack" || ! -x "$ts_modules/.bin/tsc" || ! -f "$ts_marker" || "$(cat "$ts_marker")" != "$ts_stamp" ]]; then
    echo 'Installing TypeScript dependencies...'
    cp packages/turbocommons-ts/package.json packages/turbocommons-ts/package-lock.json "$ts_root/"
    npm ci --prefix "$ts_root" --ignore-scripts
    printf '%s\n' "$ts_stamp" > "$ts_marker"
  fi

  composer_stamp="$(dependency_stamp packages/turbocommons-php/composer.json packages/turbocommons-php/composer.lock)"
  if [[ ! -f "$workspace_root/packages/turbocommons-php/vendor/autoload.php" || ! -f "$composer_marker" || "$(cat "$composer_marker")" != "$composer_stamp" ]]; then
    echo 'Installing PHP dependencies...'
    composer install \
      --working-dir="$workspace_root/packages/turbocommons-php" \
      --no-interaction \
      --no-progress \
      --prefer-dist
    printf '%s\n' "$composer_stamp" > "$composer_marker"
  fi
}

stage_distribution() {
  local distribution_root="$workspace_root/dist"
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
  cp "$workspace_root/packages/turbocommons-php/dist/turbocommons-php-$php_version.phar" \
    "$distribution_root/turbocommons-php/"

  find "$workspace_root/packages/turbocommons-ts/dist/packages" -maxdepth 1 -type f -name '*.tgz' \
    -exec cp {} "$distribution_root/turbocommons-ts/" \;

  java_version="$(sed -n 's/^version=//p' "$workspace_root/packages/turbocommons-java/version.properties")"
  find "$workspace_root/packages/turbocommons-java/build/libs" -maxdepth 1 -type f -name '*.jar' \
    -exec cp {} "$distribution_root/turbocommons-java/" \;
  printf '%s\n' "$java_version" > "$distribution_root/turbocommons-java/VERSION"

  shell_version="$(node -p "require('./packages/turbocommons-shell/package.json').version")"
  tar -czf "$distribution_root/turbocommons-shell/turbocommons-shell-$shell_version.tar.gz" \
    -C "$workspace_root/packages/turbocommons-shell" ubuntu win-powershell README.md package.json
}

if [[ "${1:-ci}" != "clean" ]]; then
  ensure_dependencies
fi

case "${1:-ci}" in
  clean)
    rm -rf \
      "$workspace_root/.nx" \
      "$workspace_root/dist" \
      "$workspace_root/packages/turbocommons-*/dist" \
      "$workspace_root/packages/turbocommons-*/target" \
      "$workspace_root/packages/turbocommons-*/build" \
      "$workspace_root/packages/turbocommons-*/bin"
    ;;
  deps)
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
