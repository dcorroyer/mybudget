<?php

declare(strict_types=1);

function fgp(): Fingerprint
{
    return new Fingerprint();
}

/**
 * @param array<string> $commands
 */
function docker(array $commands = []): Docker
{
    return new Docker($commands);
}

/**
 * @param array<string> $commands
 */
function php(array $commands = [], string $user = 'appuser'): Docker
{
    return docker(['compose', 'exec', '-u', $user, 'php', ...$commands]);
}

/**
 * @param array<string> $commands
 */
function node(array $commands = [], string $user = 'root'): Docker
{
    return docker(['compose', 'exec', '-u', $user, 'front', ...$commands]);
}

/**
 * @param array<string> $commands
 */
function composer(array $commands = [], string $user = 'appuser'): Docker
{
    return php(['composer', ...$commands], user: $user);
}

/**
 * @param array<string> $commands
 */
function console(array $commands = [], string $user = 'appuser'): Docker
{
    return php(['php', 'bin/console', ...$commands], user: $user);
}
