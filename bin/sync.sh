#!/usr/bin/env bash
# Copies the library's runtime files into a plugin: bin/sync.sh <plugin>/libraries/dfx-plugin-showcase
set -euo pipefail
src="$( cd "$( dirname "${BASH_SOURCE[0]}" )/.." && pwd )"
dest="${1:?Usage: bin/sync.sh <destination folder>}"
mkdir -p "$dest"
cp "$src/load.php" "$src/class-dfx-plugin-showcase.php" "$src/catalog.json" "$dest/"
echo "Copied DFX Plugin Showcase $(grep -oP "'version' => '\K[^']+" "$src/load.php") to $dest"
