<?php

use Castor\Attribute\AsContext;
use Castor\Attribute\AsOption;
use Castor\Attribute\AsTask;
use Castor\Context;

use function Castor\fingerprint;
use function Castor\fs;
use function Castor\hasher;
use function Castor\run;

#[AsContext(default: true)]
function default_context(): Context
{
    return new Context(
        tty: true,
        timeout: 0,
    );
}

function docker_compose(string $cmd = ''): void
{
    run("docker compose $cmd", tty: true, timeout: 0);
}


function run_in_php_container(string $cmd = ''): void
{
    $userId = posix_getuid();
    $userGroup = posix_getgid();
    docker_compose("exec -it --user=$userId:$userGroup app $cmd");
}

#[AsTask(description: 'Start the docker-compose stack')]
function start(bool $force = false): void
{
    fingerprint(
        callback: fn() => docker_compose('--profile app build --no-cache'),
        fingerprint: hasher()
            ->writeFile('Dockerfile')
            ->finish(),
        force: $force
    );
    docker_compose('--profile app up -d --wait');
}

#[AsTask(description: 'Install the project stack')]
function install(bool $force = false): void
{
    start();
    composer_install(force: $force);
    if (fs()->exists(['./config/jwt/private.pem', './config/jwt/public.pem']) === false) {
        run_in_php_container('php bin/console lexik:jwt:generate-keypair');
    }
    db_init();
    npm_init();
}

#[AsTask(description: 'Stop the docker-compose stack')]
function stop(): void
{
    docker_compose('--profile app down');
}

#[AsTask(name: 'restart', description: 'Restart the docker-compose stack')]
function restart(): void
{
    stop();
    start();
}

#[AsTask(description: 'Run the docker build command')]
function build(
    #[AsOption(description: 'Force build without cache')]
    bool $noCache = false,
): void {
    $cmd = '--profile app build';
    if ($noCache) {
        $cmd .= ' --no-cache';
    }
    docker_compose($cmd);
}

#[AsTask(description: 'Run the docker compose bash shell')]
function bash(): void
{
    docker_compose('exec -it app bash');
}

#[AsTask(name: 'composer:install', description: 'Run the composer install command')]
function composer_install(bool $force = false): void
{
    fingerprint(
        callback: fn() => run_in_php_container('composer install'),
        fingerprint: hasher()
            ->writeFile('composer.lock')
            ->writeFile('composer.json')
            ->finish(),
        force: $force
    );
}

#[AsTask(name: 'composer:update', description: 'Run the composer update command')]
function composer_update(): void
{
    run_in_php_container('composer update');
}

#[AsTask(description: 'Run the tests')]
function test(
    #[AsOption(name: 'coverage', description: 'Generate HTML coverage report')]
    bool $htmlReport = false,
): void {
    $cmd = 'vendor/bin/phpunit';
    if ($htmlReport) {
        $cmd .= ' --coverage-html=var/coverage';
    }
    run_in_php_container($cmd);
}

#[AsTask(description: 'Run the QA tools')]
function qa(): void
{
    run_in_php_container('vendor/bin/grumphp run');
}

#[AsTask(name: 'cc', description: 'Run the Clear Cache command')]
function cache_clear(): void
{
    run_in_php_container('php bin/console cache:clear');
}

#[AsTask(name: 'db:create', description: 'Create the database')]
function db_create(
    #[AsOption(description: 'For create test database')]
    bool $test = false,
): void {
    $cmd = 'php bin/console doctrine:database:create --if-not-exists';
    if ($test) {
        $cmd .= ' --env=test';
    }
    run_in_php_container($cmd);
}

#[AsTask(name: 'db:update', description: 'Run the migrations')]
function db_migrate(
    #[AsOption(description: 'For create test database')]
    bool $test = false,
): void {
    $cmd = 'php bin/console doctrine:migrations:migrate --no-interaction --allow-no-migration';
    if ($test) {
        $cmd .= ' --env=test';
    }
    run_in_php_container($cmd);
}

#[AsTask(name: 'db:fixtures', description: 'Run the fixtures')]
function db_fixtures(): void
{
    run_in_php_container('php bin/console doctrine:fixtures:load --no-interaction');
}

#[AsTask(name: 'db:init', description: 'Run the migrations and fixtures')]
function db_init(): void
{
    run_in_php_container('php bin/console doctrine:database:drop --force --if-exists');
    db_create();
    db_migrate();
    db_fixtures();
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
    run_in_php_container('npm install');
}

#[AsTask(name: 'ui:build', description: 'Run NPM build')]
function npm_build(): void
{
    run_in_php_container('npm run build');
}

#[AsTask(name: 'ui:dev', description: 'Run NPM dev')]
function npm_dev(): void
{
    run_in_php_container('npm run dev');
}
