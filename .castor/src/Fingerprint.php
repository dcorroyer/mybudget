<?php

declare(strict_types=1);

namespace TheoD\MusicAutoTagger;

use function Castor\hasher;

class Fingerprint
{
    public function php_docker(): string
    {
        return hasher()
            ->writeFile(path('.docker/php/Dockerfile', root_context()))
            ->writeFile(path('.docker/php/dev-entrypoint.sh', root_context()))
            ->writeFile(path('.docker/php/Caddyfile', root_context()))
            ->writeFile(path('.docker/php/worker.Caddyfile', root_context()))
            ->writeFile(path('.docker/php/conf.d/app.ini', root_context()))
            ->writeFile(path('.docker/php/conf.d/app.dev.ini', root_context()))
            ->writeFile(path('.docker/php/conf.d/app.prod.ini', root_context()))
            ->finish()
        ;
    }

    public function composer(): string
    {
        return hasher()
            ->writeFile(path('composer.json'))
            ->writeFile(path('composer.lock'))
            ->finish()
        ;
    }

    public function npm(): string
    {
        return hasher()
            ->writeFile(path('package.json'))
            ->writeFile(path('package-lock.json'))
            ->finish()
        ;
    }
}

function fgp(): Fingerprint
{
    return new Fingerprint();
}
