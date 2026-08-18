#!/usr/bin/env bash
set -euo pipefail

repo_root="$(cd "$(dirname "${BASH_SOURCE[0]}")/.." && pwd)"
cd "$repo_root"

required_files=(
  AI.md
  SPECIFICATIONS.md
  INSTALLATION.md
  MAINTENANCE.md
  VALIDATION.md
  AGENT-GUIDE.md
  integrations/wordpress/README.md
  integrations/wordpress/ai-md-catalogue.php
)

for required_file in "${required_files[@]}"; do
  test -s "$required_file" || {
    echo "Missing or empty required file: $required_file" >&2
    exit 1
  }
done

test "$(grep -c '^# ' AI.md)" -eq 1
grep -q '^#### specification_version: 1\.0\.0$' AI.md
grep -q '^#### metadata_version:' AI.md
grep -q '^#### last_updated:' AI.md
grep -q '^#### canonical_url: https://' AI.md
grep -q '^#### catalogue_owner:' AI.md
grep -q '^#### license:' AI.md

if grep -RInE '[[:blank:]]+$' --include='*.md' --include='*.php' .; then
  echo 'Trailing whitespace found.' >&2
  exit 1
fi

while IFS= read -r markdown_file; do
  fence_count="$(grep -c '^```' "$markdown_file" || true)"

  if (( fence_count % 2 != 0 )); then
    echo "Unbalanced code fences: $markdown_file" >&2
    exit 1
  fi
done < <(find . -type f -name '*.md' -not -path './.git/*' -print)

if command -v php >/dev/null 2>&1; then
  php -l integrations/wordpress/ai-md-catalogue.php
else
  echo 'PHP unavailable; PHP syntax check skipped locally.'
fi

echo 'Repository validation passed.'
