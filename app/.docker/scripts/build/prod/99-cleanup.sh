#!/bin/sh

echo "Cleaning up the image for production 🧹"

apt-get clean && rm -rf /var/lib/apt/lists/*

if [ -d "/app/.docker" ]; then
    rm -rf /app/.docker # Don't need the .docker directory in the image
fi

composer clear-cache && rm -rf /usr/local/bin/composer

rm -rf /app/var/cache/* /app/var/log/*

rm -rf /var/cache/* /var/log/* /usr/share/doc/* /tmp/* /var/tmp/*

# Remove Swagger UI files (huge and not required in production for the moment)
# Nelmio used only in dev (but can maybe be used in prod in the future)
rm -rf /app/vendor/nelmio/api-doc-bundle/public/swagger-ui/swagger-ui-bundle.js /app/vendor/nelmio/api-doc-bundle/public/swagger-ui/swagger-ui-bundle.js.map
