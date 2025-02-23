<?php

use Castor\Attribute\AsOption;
use Castor\Attribute\AsTask;
use Symfony\Component\Console\Input\InputOption;
use function Castor\capture;
use function Castor\io;

#[AsTask(description: 'Build and push the image to docker registry')]
function build_and_push(
    #[AsOption] ?string $target = null,
    #[AsOption(mode: InputOption::VALUE_NEGATABLE)] bool $push = true,
    #[AsOption(mode: InputOption::VALUE_NEGATABLE)] bool $noCache = false,
): void
{
    io()->title('Building the image');

    if ($target === null) {
        $target = io()->choice('Select the target', ['integration', 'prod'], 'prod');
    }

    if (! in_array($target, ['integration', 'prod'])) {
        throw new \InvalidArgumentException('The target must be either "integration" or "prod"');
    }

    $version = "$target-0.0.1";
    $currentTime = (new DateTime(timezone: new DateTimeZone('Europe/Paris')))->format('Y-m-d\TH:i:s');
    $tag = "docker-registry.theo-corp.fr/theod02/myfitness:$version";

    io()->writeln([
        "Target: $target",
        'Push: ' . ($push ? 'yes' : 'no'),
        "Version: $version",
        "Build time: $currentTime",
        "Tag: $tag",
        '',
    ]);

    if (io()->confirm('Do you want to continue?', false) === false) {
        io()->warning('Aborted');
        return;
    }

    docker(['build'])
        ->add(
            '--build-arg', "BUILD_TIME=$currentTime",
            '--build-arg', "VERSION=$version",
            '--file', symfony_context()->workingDirectory . '/Dockerfile',
            '--platform', 'linux/amd64',
            '--progress', 'plain',
            '--target', $target,
        )
        ->addIf($noCache, '--no-cache')
        ->addIf($push, '--push')
        ->add('--tag', $tag)
        ->add(symfony_context()->workingDirectory)
        ->run();

    $buildSize = capture(['docker', 'image', 'inspect', '--format', '{{.Size}}', $tag]);
    $buildSizeInMB = round($buildSize / 1024 / 1024, 2);

    io()->newLine();
    io()->success([
        'Image built successfully',
        "Image: $tag",
        "Version: $version",
        "Build time: $currentTime",
        "Size: $buildSizeInMB MB",
        '',
    ]);
}