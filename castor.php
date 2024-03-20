<?php

use Castor\Attribute\AsOption;
use Castor\Attribute\AsTask;

use Castor\Utils\Docker\DockerUtils;

use function Castor\context;
use function Castor\fingerprint;
use function Castor\fs;
use function Castor\import;
use function Castor\input;
use function Castor\io;
use function Castor\Utils\Docker\docker;
use function fingerprints\composer_fingerprint;
use function fingerprints\dockerfile_fingerprint;
use function utils\path;

import('./.castor/extras');
import('./.castor');

//import(default_context()['paths']['castor']);
import(default_context()['paths']['tools'] . '/k6/castor.php');

#[AsTask(description: 'Start project')]
function start(bool $force = false): void
{
    if (DockerUtils::isRunningInsideContainer() === false) {
        fingerprint(
            callback: static fn() => docker()->compose()->build(services: ['app'], noCache: true),
            fingerprint: dockerfile_fingerprint(),
            force: $force
        );

        docker()->compose(profile: ['app'])->up(detach: true, wait: true);
    }

    init_project();
    //docker()->compose(profile: ['worker'])->up(detach: true, wait: false);
}

#[AsTask(description: 'Stop project')]
function stop(): void
{
    docker()->compose(profile: ['app'])->down();
    //docker()->compose(profile: ['worker'])->down();
}

#[AsTask(description: 'Restart project')]
function restart(): void
{
    stop();
    start();
}

#[AsTask(description: 'Install project')]
function install(bool $force = false): void
{
    start(force: $force);
    fingerprint(callback: static fn() => composer()->install(), fingerprint: composer_fingerprint(), force: $force);
    db_reset();
    npm_init();
}

#[AsTask(description: 'Open shell in the container (default: fish)', aliases: ['sh', 'fish'])]
function shell(
    #[AsOption(description: 'If run as root')]
    bool $root = false
): void {
    $shell = input()->getArgument('command') === 'shell' ? 'fish' : input()->getArgument('command');
    $containerName = context()->data['docker']['default']->container;
    docker()->exec(
        container: $containerName,
        args: [$shell],
        interactive: true,
        tty: true,
        user: $root ? 'root' : 'www-data'
    );
}

function init_project(): void
{
//    try {
//        $files = finder()
//            ->in(path())
//            ->sortByName()
//            ->notName(['castor.php', '.castor']);
//
//        foreach ($files->directories() as $directory) {
//            try {
//                fs()->remove($directory);
//            } catch (Exception $e) {
//                // do nothing
//            }
//        }
//
//        foreach ($files->ignoreDotFiles(false)->files() as $file) {
//            try {
//                fs()->remove($file);
//            } catch (Exception $e) {
//                // do nothing
//            }
//        }
//    } catch (Exception $e) {
//        // do nothing
//    }
    if (fs()->exists(path('composer.json'))) {
        return;
    }

    $ecsContent = <<<PHP
<?php

declare(strict_types=1);

require_once dirname(__DIR__) . '/tools/ecs/BaseECSConfig.php';

return BaseECSConfig::config();
PHP;

    $rectorContent = <<<PHP
<?php

declare(strict_types=1);

require_once dirname(__DIR__) . '/tools/rector/BaseRectorConfig.php';

return BaseRectorConfig::config();
PHP;

    $sfVersion = io()->choice('Which version of Symfony do you want to use?', ['5.4', '6.4', '7.*'], '6.4');
    io()->writeln('Creating Symfony project...');

    composer()->createProject(sprintf('symfony/skeleton:"%s.*"', $sfVersion), 'tmp');
    $currentDir = path();
    fs()->mirror($currentDir . '/tmp', $currentDir);
    fs()->remove($currentDir . '/tmp');

    io()->info('Updating Symfony version...');
    composer()->update('symfony/*', withDependencies: true);

    io()->info('Setting up ECS and Rector...');
    file_put_contents($currentDir . '/ecs.php', $ecsContent);
    file_put_contents($currentDir . '/rector.php', $rectorContent);

    composer()->require(['symfony/maker-bundle', 'symfony/debug-pack'], dev: true);
    composer()->require('twig-bundle');

    $front = io()->choice('Use webpack-encore or vite ?', ['webpack-encore', 'vite'], 'webpack-encore');

    if ($front === 'webpack-encore') {
        composer()->require('symfony/webpack-encore-bundle');
    } else {
        composer()->require('pentatrion/vite-bundle');
    }

    /*$envLocalContent = file_get_contents("{$currentDir}/.env.local");
    $envLocalContent = str_replace('{PROJECT_PATH}', $currentDir, $envLocalContent);
    file_put_contents("{$currentDir}/.env.local", $envLocalContent);*/
}

#[AsTask(name: 'db:reset', description: 'Reset the database')]
function db_reset(): void
{
    symfony()->console('doctrine:database:drop --force --if-exists');
    symfony()->console('doctrine:database:create');
    symfony()->console('doctrine:schema:create');
    symfony()->console('doctrine:fixtures:load --no-interaction');
}

#[AsTask(name: 'ui:init', description: 'Run init')]
function npm_init(): void
{
    npm_install();
    npm_build();
}

#[AsTask(name: 'ui:install', description: 'Run NPM install')]
function npm_install(): void
{
    node()->install();
}

#[AsTask(name: 'ui:build', description: 'Run NPM build')]
function npm_build(): void
{
    node()->run('build');
}

#[AsTask(name: 'ui:dev', description: 'Run NPM dev')]
function npm_dev(): void
{
    node()->run('dev');
}

#[AsTask(name: 'ui:lint', description: 'Run NPM lint')]
function npm_lint(): void
{
    node()->run('lint');
}

#[AsTask(name: 'ui:format', description: 'Run NPM format')]
function npm_format(): void
{
    node()->run('format');
}
