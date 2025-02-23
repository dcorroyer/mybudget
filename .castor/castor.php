<?php

declare(strict_types=1);

use Castor\Attribute\AsArgument;
use Castor\Attribute\AsOption;
use Castor\Attribute\AsTask;

use Symfony\Component\Console\Input\InputOption;
use function Castor\capture;
use function Castor\context;
use function Castor\fingerprint;
use function Castor\fs;
use function Castor\import;
use function Castor\io;
use function Castor\run;
use function Castor\variable;
use function database\resetDatabase;
use function symfony\symfony_install;

import(__DIR__ . '/src');

#[AsTask(description: 'Build the docker containers')]
function build(bool $force = false): void
{
    $userId = context()->environment['UID'] ?? variable('user.id') ?? null;
    $groupId = context()->environment['GID'] ?? variable('user.group') ?? null;
    if ($userId === null || $groupId === null) {
        io()->error('The UID and GID environment variables must be set.');
        exit(1);
    }

    if (
        ! fingerprint(
            callback: static fn () => docker([
                'compose',
                '--progress', 'plain',
                '-f', 'compose.yaml', '-f', 'compose.override.yaml',
                'build',
                '--build-arg', 'VERSION=local-dev',
                '--build-arg', sprintf('BUILD_TIME=%s', (new DateTime(timezone: new DateTimeZone('Europe/Paris')))->format('Y-m-d\TH:i:s')),
                '--build-arg', sprintf('UID=%s', $userId),
                '--build-arg', sprintf('GID=%s', $groupId),
            ])->run(),
            id: 'docker-build',
            fingerprint: fgp()->docker(),
            force: $force || ! docker()->hasImages(['docker-php']),
        )
    ) {
        io()->note(
            'The Dockerfile or the docker-compose files have not changed since the last run, skipping the docker build command.',
        );
    }
}

#[AsTask(description: 'Build and start the docker containers')]
function start(bool $force = false): void
{
    build($force);

    docker(['compose', '-f', 'compose.yaml', '-f', 'compose.override.yaml', 'up', '-d', '--wait', '--remove-orphans'])->run();
}

#[AsTask(description: 'Stop the docker containers')]
function stop(): void
{
    docker(['compose', 'down'])->run();
}

#[AsTask(description: 'Restart the docker containers')]
function restart(bool $force = false): void
{
    stop();
    start($force);
}

#[AsTask(description: 'Install the project dependencies')]
function install(bool $force = false, bool $noStart = false): void
{
    if ($noStart === false && ! docker()->isRunningInDocker()) {
        start();
    }

    // fetchOrPushSecretKey();

    symfony_install($force);

    console(['lexik:jwt:generate-keypair', '--skip-if-exists'])->run();

    resetDatabase();
}

#[AsTask(description: 'Run the shell in the container')]
function shell(?string $user = null, string $shell = 'fish', bool $front = false): void
{
    $defaultUser = $front ? 'www-data' : 'appuser';

    if ($front) {
        node(['bash'], user: $user ?? $defaultUser)->run();
    }

    php([$shell], user: $user ?? $defaultUser)->run();
}

/**
 * @param array<string> $commands
 */
#[AsTask(name: 'console', description: 'Run Symfony console commands')]
function symfony_console(
    #[AsArgument(description: 'The Symfony console commands to run')]
    array $commands = [''],
    string $user = 'appuser',
): void {
    console($commands, user: $user)->run();
}

#[AsTask(description: 'Fetch or push the secret key from 1password')]
function fetchOrPushSecretKey(
    #[AsOption(shortcut: 'p', mode: InputOption::VALUE_NEGATABLE, description: 'Push the secret key to 1password')]
    bool $push = false,
    #[AsOption(shortcut: 'f', mode: InputOption::VALUE_NEGATABLE, description: 'Force fetching the secret key')]
    bool $force = false,
    #[AsOption(shortcut: 'r', mode: InputOption::VALUE_NEGATABLE, description: 'Rotate the secret key')]
    bool $rotate = false
): void
{
    io()->title($push ? 'Pushing the secret key to 1password...' : 'Fetching the secret key from 1password...');
    $title = 'PHP Docker Secret Key';
    $vault = 'Dev';
    $tags = 'Symfony,PHP Docker';
    $secretKeyPath = 'app/config/secrets/dev/dev.decrypt.private.php';
    $secretKeyExists = fs()->exists($secretKeyPath);

    $items = capture(['op', 'item', 'list', '--vault', $vault, '--tags', $tags, '--format', 'json']);
    $items = json_decode($items, true, 512, JSON_THROW_ON_ERROR);
    $items = array_filter($items, static fn (array $item) => $item['title'] === $title);

    if (count($items) > 1) {
        io()->error([
            'There are multiple secret keys with the same title and tags in 1password.',
            'Please remove the duplicates and try again.',
            "The secret key title is '{$title}' and the tags are '{$tags}'.",
            "The secret key vault is '{$vault}'.",
        ]);
        return;
    }

    $existing1PasswordSecretKey = $items ? array_shift($items) : null;

    if ($push && ! $secretKeyExists && ! $rotate) {
        io()->error([
            'The secret key file does not exist, cannot push a new secret key.',
            'Generate a new secret key first. Run castor console "secrets:generate-keys" to generate a new secret key.',
        ]);
        return;
    }

    if ($rotate) {
        io()->info(! $secretKeyExists ? 'Generating a new secret key...' : 'Rotating the secret key...');
        symfony_console(['secrets:generate-keys', '--rotate']);
        $push = true;
    }

    if ($push) {
        if ($existing1PasswordSecretKey) {
            io()->info('Updating the secret key in 1password...');
            run(['op', 'document', 'edit', $existing1PasswordSecretKey['id'], $secretKeyPath]);
        } else {
            io()->info('Pushing the secret key to 1password...');
            run(['op', 'document', 'create', $secretKeyPath, '--title', $title, '--vault', $vault, '--tags', $tags]);
        }
        return;
    }

    if ($secretKeyExists && ! $force) {
        io()->info([
            "The secret key file already exists at {$secretKeyPath}.",
            "If you want to push a new secret key, use the --push option.",
            "If you want to fetch a new secret key, use the --force option.",
        ]);
        return;
    }

    if ($force && $secretKeyExists) {
        io()->info('--force option is set, removing the existing secret key file...');
        fs()->remove($secretKeyPath);
    }

    io()->info('Fetching the secret key from 1password...');
    run(['op', 'document', 'get', $title, '--vault', $vault, '--out-file', $secretKeyPath]);

    io()->success('The secret key has been fetched successfully.');
}