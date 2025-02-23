#!/bin/sh

set -e

echo "Running build scripts"
if [ -d "/docker-scripts/build" ]; then
    echo "Build scripts directory exists"
    # foreach file in order of filename
    for f in $(ls /docker-scripts/build/* | sort); do
        if [ "$f" = "/docker-scripts/build/run.sh" ]; then
            continue
        fi
        if [ -f "$f" ] && [ -x "$f" ]; then
            echo ""
            echo "========================================"
            echo "Executing $f"
            echo "========================================"

            "$f"

            echo "========================================"
            echo "Executed $f"
            echo "========================================"
            echo ""
        fi
    done
    echo "Build scripts directory processed"
else
    echo "Build scripts directory does not exist"
fi

chown -R ${UID}:${GID} /app

exec "$@"
