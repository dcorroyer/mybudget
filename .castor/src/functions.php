<?php

declare(strict_types=1);

namespace TheoD\MusicAutoTagger;

use Castor\Context;
use TheoD\MusicAutoTagger\Docker\DockerRunner;

function docker(?Context $castorContext = null): DockerRunner
{
    return new DockerRunner($castorContext);
}
