<?php

use Castor\Attribute\AsOption;
use Castor\Attribute\AsTask;

use function Castor\run;

function docker_compose(string $cmd = ''): void
{
    run("docker compose $cmd", tty: true, timeout: 0);
}


function run_in_php_container(string $cmd = ''): void
{
    $userId = posix_getuid();
    $userGroup = posix_getgid();
    docker_compose("exec -it --user=$userId:$userGroup php $cmd");
}

#[AsTask(description: 'Start the docker-compose stack')]
function start(): void
{
    docker_compose('up -d');
}

#[AsTask(description: 'Stop the docker-compose stack')]
function stop(): void
{
    docker_compose('down');
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
    $cmd = 'build';
    if ($noCache) {
        $cmd .= ' --no-cache';
    }
    docker_compose($cmd);
}

#[AsTask(description: 'Run the docker compose bash shell')]
function bash(): void
{
    docker_compose('exec -it php bash');
}

#[AsTask(name: 'composer:install', description: 'Run the composer install command')]
function composer_install(): void
{
    run_in_php_container('composer install');
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
): void
{
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
    run_in_php_container('bin/console cache:clear');
}

#[AsTask(name: 'db:create', description: 'Create the database')]
function db_create(
    #[AsOption(description: 'For create test database')]
    bool $test = false,
): void {
    $cmd = 'bin/console doctrine:database:create';
    if ($test) {
        $cmd .= ' --env=test';
    }
    docker_compose($cmd);
}

#[AsTask(name: 'db:update', description: 'Run the migrations')]
function db_migrate(
    #[AsOption(description: 'For create test database')]
    bool $test = false,
): void {
    $cmd = 'bin/console doctrine:migrations:migrate';
    if ($test) {
        $cmd .= ' --env=test';
    }
    docker_compose($cmd);
}

#[AsTask(name: 'db:fixtures', description: 'Run the fixtures')]
function db_fixtures(): void
{
    run_in_php_container('bin/console doctrine:fixtures:load');
}

#[AsTask(name: 'db:init', description: 'Run the migrations and fixtures')]
function db_init(): void
{
    run_in_php_container('bin/console doctrine:database:drop --force');
    db_create();
    db_migrate();
    db_fixtures();
}

//#[AsTask(name: 'ui:install', description: 'Run NPM install')]
//function npm_install(): void
//{
//    docker_compose('run --rm node npm install');
//}
//
//#[AsTask(name: 'ui:build', description: 'Run NPM build')]
//function npm_build(): void
//{
//    docker_compose('run --rm node npm run build');
//}
//
//#[AsTask(name: 'ui:dev', description: 'Run NPM watch')]
//function npm_watch(): void
//{
//    docker_compose('run --rm node npm run watch');
//}
