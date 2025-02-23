<?php

declare(strict_types=1);

use Castor\Attribute\AsListener;
use Castor\Event\BeforeExecuteTaskEvent;
use Castor\Event\ProcessStartEvent;
use Symfony\Component\Process\ExecutableFinder;

use function Castor\check;
use function Castor\finder;
use function Castor\fingerprint_exists;
use function Castor\fs;
use function Castor\io;
use function Castor\task;
use function symfony\symfony_install;

#[AsListener(BeforeExecuteTaskEvent::class)]
function check_docker_presence(): void
{
    check(
        'Check if the docker is installed',
        'Docker is required to run this project. Please install it.',
        static fn (): bool => (new ExecutableFinder())->find('docker') !== null,
    );
}

#[AsListener(BeforeExecuteTaskEvent::class)]
function prevent_running_certains_command_inside_docker(): void
{
    $taskName = task()?->getName();

    if (in_array($taskName, ['start', 'stop', 'restart', 'build'], true)) {
        docker()->preventRunningInsideDocker();
    }
}

#[AsListener(ProcessStartEvent::class)]
function log_process_start(ProcessStartEvent $event): void
{
    $commands = $event->process->getCommandLine();
    $commands = array_map(static fn (string $cmd) => trim($cmd, "'"), explode(' ', $commands));
    $commands = implode(' ', $commands);

    $silents = ['echo $HOME', 'docker compose images', 'docker compose ps --services', 'id -u', 'id -g'];

    if (in_array($commands, $silents, true)) {
        return;
    }

    $prefix = str_contains($commands, 'docker') ? '[docker]' : '[local]';
    io()->writeln("> <info>{$prefix}</info> <comment>{$commands}</comment>");
}

#[AsListener(BeforeExecuteTaskEvent::class)]
function ensure_project_has_run_setup_before_any(): void
{
    $taskName = task()?->getName();

    if ($taskName === 'setup') {
        return;
    }

    $files = finder()
        ->in(root_context()->workingDirectory)
        ->name(['*.yml', '*.yaml', '*.json', '*.bru'])
        ->notName(['vendor', 'node_modules'])
        ->contains(['{{PREFIX_CONTAINER}}', '{{PREFIX_URL}}', 'PROJECT_NAME'])
        ->count()
    ;

    if ($files > 0) {
        io()->error(['The project has not been set up yet.', 'Please run the `castor setup` command first.']);

        exit(1);
    }
}

#[AsListener(BeforeExecuteTaskEvent::class)]
function check_docker_containers_is_running(): void
{
    if (docker()->isRunningInDocker()) {
        return;
    }

    $taskName = task()?->getName();

    if ($taskName === 'deploy' || str_contains($taskName, 'deploy:')) {
        return;
    }

    if (in_array($taskName, ['reset-project', 'setup', 'start', 'stop', 'restart', 'install', 'build'], true)) {
        return;
    }

    $notRunningServices = docker()->isServicesRun(['php', 'database']);
    if ($notRunningServices) {
        io()->note('The following services are not running:');
        io()->listing($notRunningServices);

        io()->error([
            'Please run the `castor start` command to start the services.',
            'If start fail, debug the issue by seeing the logs with `docker compose logs <service>`.',
        ]);

        exit(1);
    }
}

#[AsListener(BeforeExecuteTaskEvent::class)]
function autorun_install_when_missing_deps(): void
{
    $taskName = task()?->getName();

    if (in_array($taskName, ['install', 'reset-project', 'setup', 'start', 'stop', 'restart', 'build'], true)) {
        return;
    }

    $backendFolder = symfony_context()->workingDirectory;

    $missingDeps = [];

    if (fs()->exists("{$backendFolder}/composer.json") && ! fs()->exists("{$backendFolder}/vendor")) {
        $missingDeps['Composer dependencies'] = static fn () => symfony_install(force: true);
    }

    if ($missingDeps) {
        io()->warning('Some dependencies seem to be missing:');
        io()->listing(array_keys($missingDeps));

        io()->writeln('Installing them now...');
        foreach ($missingDeps as $depName => $installTask) {
            io()->writeln("Installing {$depName}...");
            $installTask();
        }
    }
}

#[AsListener(BeforeExecuteTaskEvent::class)]
function check_outdated_deps(): void
{
    $taskName = task()?->getName();

    if (in_array($taskName, ['install', 'reset-project', 'setup', 'start', 'stop', 'restart', 'build'], true)) {
        return;
    }

    $backendFolder = symfony_context()->workingDirectory;

    $outdatedFingerprints = [];

    if (fs()->exists("{$backendFolder}/composer.json") && fingerprint_exists(
        'composer-install',
        fgp()->composer(),
    ) === false) {
        $outdatedFingerprints['Composer dependencies'] = null;
    }

    if ($outdatedFingerprints) {
        io()->warning('Some dependencies seem to be outdated:');
        io()->listing(array_keys($outdatedFingerprints));
    }
}
