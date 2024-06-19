<?php

declare(strict_types=1);

namespace TheoD\MusicAutoTagger;

use Castor\Attribute\AsContext;
use Castor\Context;

\define('ROOT_DIR', \dirname(__DIR__, 2));

#[AsContext]
function root_context(): Context
{
    return new Context(
        environment: [
            'DOCKER_USER_ID' => getmyuid(),
            'DOCKER_GROUP_ID' => getmygid(),
        ],
        workingDirectory: ROOT_DIR
    );
}

#[AsContext(default: true)]
function app_context(): Context
{
    return root_context()
        ->withWorkingDirectory(ROOT_DIR . '/app')
    ;
}
