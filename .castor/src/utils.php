<?php

declare(strict_types=1);

namespace utils;

function ensure_directory_exists(string $directory): void
{
    if (! is_dir($directory)) {
        if (! mkdir($directory, 0o777, true) && ! is_dir($directory)) {
            throw new \RuntimeException(\sprintf('Directory "%s" was not created', $directory));
        }
    }
}
