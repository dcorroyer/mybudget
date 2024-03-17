#!/bin/bash
set -e

# Ensure that project vendor is installed
if [ -f "/app/composer.json" ]; then
    echo "Installing project dependencies"
    cd /app
    composer install # this is currently installed as root, see how to run this part as www-data
fi


# Ensure tools are installed in /tools (/tools/ecs, /tools/phpstan, etc.)
tools=(ecs phpstan rector)

for tool in "${tools[@]}"
do
    tool_directory="/tools/$tool"

    if [ -d "$tool_directory" ] && [ -f "$tool_directory/composer.json" ]; then
        echo "Installing $tool"
        cd "$tool_directory"
        composer install
    fi
done

chmod +x /tools/bin/* || true
ln -s /tools/bin/* /usr/local/bin/ || true

# Add /tools/bin to PATH
export PATH=$PATH:/tools/bin

exec docker-php-entrypoint "$@"
