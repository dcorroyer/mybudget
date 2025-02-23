#!/usr/bin/env sh

set -e

echo "Executing entrypoint.sh"

echo "Running runtime scripts"
if [ -d "/docker-scripts/runtime" ]; then
    echo "Runtime scripts directory exists"
    for f in $(ls /docker-scripts/runtime/* | sort); do
        if [ -f "$f" ] && [ -x "$f" ]; then
            echo ""
            echo "========================================"
            echo "Executing $f"
            echo "========================================"
            echo ""

            "$f"

            echo ""
            echo "========================================"
            echo "Executed $f"
            echo "========================================"
        fi
    done
    echo ""
    echo "Runtime scripts directory processed"
else
    echo "Runtime scripts directory does not exist"
fi

exec "$@"
