#!/usr/bin/env bash
set -Eeuo pipefail

cd "$(dirname "${BASH_SOURCE[0]}")/.."
task="${1:-ci}"
shift || true

case "$task" in
  build) command=(build) ;;
  test) command=(test) ;;
  lint) command=(lint) ;;
  package) command=(package) ;;
  ci) command=(ci) ;;
  shell) command=(bash) ;;
  *) echo "Usage: $0 {build|test|lint|package|ci|shell} [arguments...]" >&2; exit 2 ;;
esac

docker compose run --rm --build toolbox "${command[@]}" "$@"
