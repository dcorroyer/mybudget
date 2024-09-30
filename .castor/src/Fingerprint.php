<?php

declare(strict_types=1);

namespace TheoD\MusicAutoTagger;

use function Castor\hasher;

class Fingerprint
{
    public function php_docker(): string
    {
        return hasher()
            ->writeFile(path('Dockerfile', root_context()))
            ->writeFile(path('.docker/frankenphp/docker-entrypoint.sh', root_context()))
            ->writeFile(path('.docker/frankenphp/Caddyfile', root_context()))
            ->writeFile(path('.docker/frankenphp/worker.Caddyfile', root_context()))
            ->writeFile(path('.docker/frankenphp/conf.d/10-app.ini', root_context()))
            ->writeFile(path('.docker/frankenphp/conf.d/20-app.dev.ini', root_context()))
            ->writeFile(path('.docker/frankenphp/conf.d/20-app.prod.ini', root_context()))
            ->finish()
        ;
    }

    public function composer(): string
    {
        return hasher()
            ->writeFile(path('composer.json', app_context()))
            ->writeFile(path('composer.lock', app_context()))
            ->finish()
        ;
    }

    public function npm(): string
    {
        return hasher()
            ->writeFile(path('package.json', app_context()))
            ->writeFile(path('pnpm-lock.yaml', app_context()))
            ->finish()
        ;
    }
}

function fgp(): Fingerprint
{
    return new Fingerprint();
}
