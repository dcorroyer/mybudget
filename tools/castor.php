<?php

use Castor\Attribute\AsArgument;
use Castor\Attribute\AsContext;
use Castor\Attribute\AsOption;
use Castor\Attribute\AsTask;
use Castor\Context;
use TheoD\MusicAutoTagger\ContainerDefinitionBag;
use function Castor\context;
use function Castor\finder;
use function Castor\fs;
use function Castor\hasher;
use function Castor\io;
use function Castor\run;
use function TheoD\MusicAutoTagger\delayed_fingerprint;
use function TheoD\MusicAutoTagger\root_context;
use function TheoD\MusicAutoTagger\Runner\composer;


function getHash(string $toolDirectory): string
{
    return hasher()
        ->writeFile("{$toolDirectory}/composer.json")
        ->writeFile("{$toolDirectory}/composer.lock")
        ->finish();
}

function getToolDirectories(): array
{
    $directoriesFinder = finder()
        ->directories()
        ->in(qa_context()->workingDirectory)
        ->notName(['bin', 'k6'])
        ->depth(0);

    $directories = [];
    foreach ($directoriesFinder as $directory) {
        $directories[$directory->getFilename()] = $directory->getPathname();
    }

    return $directories;
}

#[AsContext]
function qa_context(): Context
{
    return root_context()->withWorkingDirectory(__DIR__);
}

#[AsTask(name: 'tools:install')]
function install_tools(): void
{
    io()->writeln('Checking tools installation');
    foreach (getToolDirectories() as $toolName => $toolDirectory) {
        io()->write("{$toolDirectory}...");
        if (!fs()->exists("{$toolDirectory}/composer.json")) {
            io()->error("The tool {$toolDirectory} does not contain a composer.json file");
            exit(1);
        }

        $needForceInstall = fs()->exists("{$toolDirectory}/vendor") === false;

        delayed_fingerprint(
            callback: static function () use ($toolName) {
                io()->write(' Installing...');
                composer(context()->withQuiet())
                    ->withContainerDefinition(ContainerDefinitionBag::tools($toolName))
                    ->install()
                    ->add("--working-dir=\"/tools/{$toolName}\"")
                    ->run();
            },
            fingerprint: fn() => getHash($toolDirectory),
            force: $needForceInstall
        );
        io()->writeln(' <info>OK</info>');
    }
    io()->newLine();
}

#[AsTask(name: 'tools:update')]
function update_tools(
    #[AsArgument]
    string $tool = '',
    #[AsOption]
    bool   $all = false
): void
{
    if ($tool === '' && !$all) {
        io()->error('You must specify a tool to update or use the --all option');
        exit(1);
    }

    $tools = getToolDirectories();
    if (! $all) {
        if (! isset($tools[$tool])) {
            io()->error("The tool {$tool} does not exist");
            exit(1);
        }

        $tools = [$tool => $tools[$tool]];
    }

    foreach ($tools as $toolName => $toolDirectory) {
        io()->write("{$toolDirectory}... Updating...");
        if (!fs()->exists("{$toolDirectory}/composer.json")) {
            io()->error("The tool {$toolDirectory} does not contain a composer.json file");
            exit(1);
        }

        $containerDefinition = ContainerDefinitionBag::php();
        $containerDefinition->workingDirectory = "/tools/{$toolName}";
        composer(qa_context()->withQuiet())
            ->withContainerDefinition($containerDefinition)
            ->update()
            ->add("--working-dir=\"/tools/{$toolName}\"")
            ->run();
        io()->writeln(' <info>OK</info>');
    }
}