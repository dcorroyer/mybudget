#!/bin/sh

set -e

echo "Checking if Symfony is installed (composer vendor)"

if [ -f /app/composer.json ]; then
    echo "Detected Symfony application (composer.json)"

    echo "Installing Symfony dependencies"
    composer install --no-progress --no-interaction || echo "📦 Composer install failed. Please check manually."
fi
