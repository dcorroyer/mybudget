<?php

declare(strict_types=1);

namespace symfony;

use function Castor\fingerprint;
use function Castor\fs;
use function Castor\io;

function symfony_install(bool $force = false): void
{
    if (
        ! fingerprint(
            callback: static fn () => composer(['install'])->run(),
            id: 'composer-install',
            fingerprint: fgp()->composer(),
            force: $force || ! fs()->exists(symfony_context()->workingDirectory . '/vendor'),
        )
    ) {
        io()->note(
            'The composer.lock file has not changed since the last run, skipping the composer install command.',
        );
    }
}
