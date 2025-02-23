<?php

declare(strict_types=1);

use Castor\Attribute\AsContext;
use Castor\Context;

use function Castor\capture;
use function Castor\load_dot_env;

define('ROOT_DIR', dirname(__DIR__, 2));
define('USER_ID', capture(['id', '-u']));
define('GROUP_ID', capture(['id', '-g']));

#[AsContext(name: 'root', default: true)]
function root_context(): Context
{
    load_dot_env(ROOT_DIR . '/app/.env');

    return new Context(
        data: [
            'user.id' => USER_ID,
            'user.group' => GROUP_ID,
        ],
        environment: $_SERVER,
        workingDirectory: ROOT_DIR,

    );
}

#[AsContext(name: 'symfony')]
function symfony_context(): Context
{
    return root_context()
        ->withWorkingDirectory(ROOT_DIR . '/app')
        ->withData([
            'name' => 'symfony',
            'registry' => 'docker-registry.theo-corp.fr',
            'image' => 'theod02/demo-app-symfony',
        ])
    ;
}

#[AsContext(name: 'frontend')]
function frontend_context(): Context
{
    return root_context()
        ->withWorkingDirectory(ROOT_DIR . '/front')
        ->withData([
            'name' => 'frontend',
            'registry' => 'docker-registry.theo-corp.fr',
            'image' => 'theod02/demo-app-frontend',
        ]);
}
