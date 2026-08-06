#!/usr/bin/env bash
set -Eeuo pipefail

cd "$(dirname "${BASH_SOURCE[0]}")/.."
task="${1:-ci}"
shift || true

case "$task" in
  build) command=(npm run build) ;;
  test) command=(npm test) ;;
  lint) command=(npm run lint) ;;
  package) command=(npm run package) ;;
  ci) command=(npm run ci) ;;
  shell) command=(bash) ;;
  *) echo "Usage: $0 {build|test|lint|package|ci|shell} [arguments...]" >&2; exit 2 ;;
esac

docker compose run --rm toolbox "${command[@]}" "$@"
