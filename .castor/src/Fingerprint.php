<?php

declare(strict_types=1);

use function Castor\finder;
use function Castor\hasher;

class Fingerprint
{
    public function docker(): string
    {
        $backendFolder = symfony_context()->workingDirectory;

        return hasher()
            ->writeWithFinder(finder()->in($backendFolder)->name(['Dockerfile', 'compose.*'])->files())
            ->writeWithFinder(finder()->in("{$backendFolder}/.docker")->files())
            ->finish()
        ;
    }

    public function composer(): string
    {
        $folder = symfony_context()->workingDirectory;

        return hasher()
            ->writeFile("{$folder}/composer.json")
            ->writeFile("{$folder}/composer.lock")
            ->finish()
        ;
    }
}
