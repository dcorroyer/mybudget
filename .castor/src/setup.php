<?php

declare(strict_types=1);

use Castor\Attribute\AsTask;

use function Castor\finder;
use function Castor\fs;
use function Castor\io;
use function Castor\run;
use function Symfony\Component\String\u;

#[AsTask]
function setup(): void
{
    io()->writeln('Welcome to the setup wizard!');

    io()->section('Project');

    io()->writeln('Please provide the following information:');
    $projectName = io()->ask('What is the name of the project?');
    $containerPrefix = io()->ask(
        'What is the container prefix?',
        u($projectName)->snake()->replace('_', '-')->toString()
    );
    $urlPrefix = io()->ask('What is the url prefix?', u($projectName)->snake()->replace('_', '-')->toString());

    $files = finder()
        ->in(root_context()->workingDirectory)
        ->name(['*.yml', '*.yaml', '*.json', '*.bru'])
        ->notName(['vendor', 'node_modules'])
        ->files()
    ;

    foreach ($files as $file) {
        $content = $file->getContents();
        $content = u($content)
            ->replace('{{PROJECT_NAME}}', $projectName)
            ->replace('{{PREFIX_URL}}', $urlPrefix)
            ->replace('{{PREFIX_CONTAINER}}', $containerPrefix)
            ->toString()
        ;

        file_put_contents($file->getPathname(), $content);
    }

    io()->success('Setup completed!');

    io()->info([
        'Please run the following commands to start the services',
        '* castor install (automatically start the services)',
        '',
        'You can access the project at the following URLs after the services are started:',
        " - Frontend: https://{$urlPrefix}.web.localhost",
        " - Backend: https://{$urlPrefix}.api.localhost/api",
        '',
        'You can remove ensure_project_has_run_setup_before_any() listener from .castor/src/listeners.php now (manually)',
        '',
        'If you want to restart from a brand new Symfony installation you can run the `castor reset-project` task',
    ]);
}

#[AsTask(description: 'Restart from scratch a new Symfony project')]
function reset_project(): void
{
    io()->title('Resetting the project...');

    io()->info([
        'Before resetting please make sure you have committed all your changes',
        'This task will remove all files and directories except for the .docker and vendor-bin directories, and the compose.* and Dockerfile files',
        '',
        'If you want to customize installation you can set which version you want in compose.override.yaml with SYMFONY_VERSION',
        '',
        'You can also set SYMFONY_ADDITIONAL_PACKAGES and SYMFONY_ADDITIONAL_DEV_PACKAGES to add additional packages by default',
        'You can check in .docker/php/autorun/10-install-symfony.sh to see default packages, and values used for the installation (you can modify them, if you want)',
    ]);

    if (io()->confirm('Are you sure you want to reset the project? (ALL FILES WILL BE DELETED)') === false) {
        io()->writeln('Project reset aborted');

        return;
    }

    io()->section('Stopping services');
    stop();

    io()->section('Removing files');
    $directories = finder()
        ->in(symfony_context()->workingDirectory)
        ->directories()
        ->depth(0)
        ->notName(['.docker', '.castor', 'vendor-bin'])
        ->getIterator()
    ;

    $files = finder()
        ->in(symfony_context()->workingDirectory)
        ->ignoreDotFiles(false)
        ->files()
        ->depth(0)
        ->notName(['compose.*', 'Dockerfile'])
        ->getIterator()
    ;

    $directoriesString = array_map(
        static fn (SplFileInfo $dir) => $dir->getRealPath() . '/',
        iterator_to_array($directories)
    );
    $filesString = array_map(static fn (SplFileInfo $file) => $file->getRealPath(), iterator_to_array($files));
    run(['rm', '-rf', ...$directoriesString, ...$filesString], allowFailure: true);
    io()->success('Project reset');

    try {
        install();
    } catch (Exception $e) {
        docker(['compose', 'restart', 'php'])->run();
    }

    io()->success('Project reset completed');

    $phpunitXml = symfony_context()->workingDirectory . '/phpunit.xml.dist';
    if (fs()->exists($phpunitXml)) {
        fs()->remove($phpunitXml);
        io()->title('Setup phpunit configuration');
        io()->note('PHPUnit require manual configuration for the first time');
        composer(['run', 'phpunit', '--', '--generate-configuration'])->run();
    }
}
