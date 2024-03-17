<?php

declare(strict_types=1);

namespace fingerprints;

use function Castor\finder;
use function Castor\hasher;
use function utils\path;

function dockerfile_fingerprint(): string
{
    return hasher()
        ->writeWithFinder(finder()->in(\ROOT_DIR . '/.docker'))
        ->finish()
    ;
}

function composer_fingerprint(): string
{
    return hasher()
        ->writeFile(path('composer.json'))
        ->writeFile(path('composer.lock'))
        ->finish()
    ;
}
