<?php

use Castor\Attribute\AsTask;

use function Castor\finder;
use function Castor\io;
use function Castor\run;

#[AsTask]
function k6(): void
{
    $scripts = finder()
        ->in(__DIR__)
        ->name('*.js');

    $scripts = array_map(static fn($file) => $file->getRelativePathname(), iterator_to_array($scripts));

    $k6 = io()->choice('Select a script', array_flip($scripts), 0);
    run("docker run --rm -i --network traefik -v .:/app -w /app grafana/k6 run $k6", path: __DIR__, timeout: 0);
}