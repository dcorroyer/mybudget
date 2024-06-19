<?php

declare(strict_types=1);

namespace TheoD\MusicAutoTagger;

use Castor\Context;
use TheoD\MusicAutoTagger\Docker\DockerRunner;

use function Castor\fingerprint;
use function Castor\fingerprint_save;

function docker(?Context $castorContext = null): DockerRunner
{
    return new DockerRunner($castorContext);
}

/**
 * The diff with the original function is the forced save of the fingerprint after the callback is executed.
 *
 * This can be useful in case for example like composer update files but change nothing in it :)
 */
function delayed_fingerprint(callable $callback, callable $fingerprint, bool $force = false): bool
{
    $result = fingerprint($callback, $fingerprint(), $force);

    fingerprint_save($fingerprint());

    return $result;
}
