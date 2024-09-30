<?php

declare(strict_types=1);

use Castor\Attribute\AsContext;
use Castor\Attribute\AsOption;
use Castor\Attribute\AsTask;
use Castor\Context;
use PHLAK\SemVer\Version;

use function Castor\io;
use function Castor\run;
use function Castor\variable;
use function Castor\with;

#[AsContext(name: 'docker')]
function docker(): Context
{
    return new Context(
        data: [
            'registry' => 'docker-registry.dcorroyer.fr/mybudget',
            'image' => 'mybudget',
        ],
    );
}

#[AsTask(description: 'Login to the Docker registry')]
function login(): void
{
    io()->writeln(sprintf('Logging in to %s...', variable('registry')));
    $username = io()->ask('Username: ');
    $password = io()->askHidden('Password: ');

    with(static function () use ($username, $password) {
        $command = sprintf('docker login %s -u %s -p %s', variable('registry'), $username, $password);

        io()->writeln('Logging in to Docker registry...');
        run($command);
    }, context: docker());
}

#[AsTask(description: 'Build the Docker image')]
function build(): void
{
    with(static function () {
        $command = sprintf(
            'docker build --build-arg BUILD_TIME="%s" -t %s .',
            (new DateTime(timezone: new DateTimeZone('Europe/Paris')))->format('Y-m-d\TH:i:s'),
            variable('image'),
        );

        io()->writeln('Building Docker image...');
        run($command);
    }, context: docker());
}

#[AsTask(description: 'Tag the Docker image')]
function tag(string $tag = 'latest'): void
{
    with(static function () use ($tag) {
        $command = sprintf(
            'docker tag %s "%s/%s:%s"',
            variable('image'),
            variable('registry'),
            variable('image'),
            $tag,
        );

        io()->writeln('Tagging Docker image...');
        run($command);
    }, context: docker());
}

#[AsTask(description: 'Push the Docker image')]
function push(string $tag): void
{
    with(static function () use ($tag) {
        $command = sprintf('docker push "%s/%s:%s"', variable('registry'), variable('image'), $tag);

        io()->writeln('Pushing Docker image...');
        run($command);
    }, context: docker());
}

#[AsTask(description: 'Build and push the Docker image')]
function buildAndPush(string $tag = 'latest'): void
{
    with(static function () use ($tag) {
        io()->section('Building docker image...');
        build();

        io()->section('Tagging docker image...');
        tag($tag);

        io()->section('Pushing docker image...');
        push($tag);

        $registry = variable('registry');
        $image = variable('image');
        io()->success("Done! 🎉 Image {$image}:{$tag} pushed to {$registry}/{$image}:{$tag}");
    }, context: docker());
}

#[AsTask(description: 'Deploy the Docker image')]
function deploy(
    #[AsOption(name: 'override', description: 'Keep the current version, and rebuild the image')]
    bool $override = false,
): void {
    if (! file_exists('VERSION')) {
        file_put_contents('VERSION', 'v0.0.0-dev');
    }

    $textVersion = file_get_contents('VERSION');
    $version = Version::parse($textVersion);

    io()->writeln(sprintf('Current version is %s', $version));

    if ($override) {
        io()->warning('Version overriden');
        if (io()->confirm('You are sure you want to override the version?')) {
            buildAndPush((string) $version);
        }

        return;
    }

    $releaseType = io()->choice('Release type', ['patch', 'minor', 'major', 'hotfix', 'prerelease'], 'patch');

    switch ($releaseType) {
        case 'patch':
            $version->incrementPatch();
            break;
        case 'minor':
            $version->incrementMinor();
            break;
        case 'major':
            $version->incrementMajor();
            break;
        case 'hotfix':
            $version->incrementPatch();
            $version->setPreRelease(null);
            break;
        case 'prerelease':
            if ($version->isPreRelease()) {
                $version->incrementPreRelease();
            } else {
                $version->setPreRelease(io()->ask('Pre-release name: ', 'pre-release'));
            }
            break;
        default:
            throw new RuntimeException('Invalid release type');
    }

    io()->writeln(sprintf('New version is %s', $version));

    if (io()->confirm('Do you want to build and push the image?')) {
        file_put_contents('VERSION', (string) $version);
        buildAndPush((string) $version);
    }
}
