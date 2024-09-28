<?php

declare(strict_types=1);

namespace TheoD\MusicAutoTagger;

use Castor\Attribute\AsListener;
use Castor\Event\AfterExecuteTaskEvent;
use Castor\Event\BeforeExecuteTaskEvent;
use Symfony\Component\Process\ExecutableFinder;

use function Castor\context;
use function Castor\fingerprint_exists;
use function Castor\input;
use function Castor\io;

#[AsListener(BeforeExecuteTaskEvent::class, priority: 1000)]
function check_tool_deps(BeforeExecuteTaskEvent $event): void
{
    if ((new ExecutableFinder())->find('docker') === null) {
        io()->error(
            [
                'Docker is required for running this application',
                'Check documentation: https://docs.docker.com/engine/install',
            ],
        );
        exit(1);
    }
}

#[AsListener(BeforeExecuteTaskEvent::class, priority: 900)]
function check_docker_is_running(BeforeExecuteTaskEvent $event): void
{
    if (\in_array(
        $event->task->getName(),
        ['start', 'setup', 'install', 'stop', 'restart', 'prod:up', 'prod:build'],
        true
    )) {
        return;
    }

    $context = context()->withQuiet();
    if (str_contains(
        docker($context)->compose('ps')->run()->getOutput(),
        ContainerDefinitionBag::php()->name,
    ) === false) {
        io()->note('Docker containers are not running. Starting them.');
        start();
    } else {
        if (fingerprint_exists('docker', fgp()->php_docker()) === false) {
            io()->note(
                'Some docker related files seems to has been changed. Please consider to restart the containers.',
            );
        }
    }
}

#[AsListener(BeforeExecuteTaskEvent::class, priority: 800)]
#[AsListener(AfterExecuteTaskEvent::class, priority: 800)]
function check_projects_deps(BeforeExecuteTaskEvent|AfterExecuteTaskEvent $event): void
{
    if (input()->hasOption('no-check') && input()->getOption('no-check') === true) {
        return;
    }

    if ($event instanceof BeforeExecuteTaskEvent && \in_array(
        $event->task->getName(),
        ['start', 'setup', 'stop', 'restart', 'install'],
        true,
    )) {
        return;
    }

    if (\in_array(
        $event->task->getName(),
        ['shell', 'setup', 'install', 'prod:up', 'prod:build', 'tools:install'],
        true
    )) {
        return;
    }

    $deps = [];

    if (is_file(app_context()->workingDirectory . '/composer.json')) {
        $deps['Composer'] = app_context()->workingDirectory . '/vendor';
    }

    if (
        is_file(app_context()->workingDirectory . '/package.json')
        || is_file(app_context()->workingDirectory . '/yarn.lock')
    ) {
        $deps['Node Modules'] = app_context()->workingDirectory . '/node_modules';
    }

    foreach (getToolDirectories() as $directoryName => $directoryFullPath) {
        $deps["QA - {$directoryName}"] = "{$directoryFullPath}/vendor";
    }

    $missingDeps = [];

    foreach ($deps as $depName => $path) {
        if (is_dir($path) === false) {
            $missingDeps[] = $depName;
        }
    }

    if ($missingDeps !== []) {
        io()->newLine();
        io()->error('Some dependencies are missing:');
        io()->listing($missingDeps);

        if (io()->confirm('Do you want to install them now?') === false) {
            io()->note('Run `castor install` to install them.');
            exit(1);
        }

        install();
    }

    // Check if deps is latest
    $outdatedDeps = [];

    if (is_file(app_context()->workingDirectory . '/composer.json')) {
        if (fingerprint_exists('composer', fgp()->composer()) === false) {
            $outdatedDeps[] = 'Composer';
        }
    }

    if (
        is_file(app_context()->workingDirectory . '/package.json')
        || is_file(app_context()->workingDirectory . '/yarn.lock')
    ) {
        if (fingerprint_exists('npm', fgp()->npm()) === false) {
            $outdatedDeps[] = 'Node Modules';
        }
    }

    if ($outdatedDeps !== []) {
        io()->newLine();
        io()->warning('Some dependencies are outdated:');
        io()->listing($outdatedDeps);

        io()->note('Run `castor install` to install them.');
    }
}
