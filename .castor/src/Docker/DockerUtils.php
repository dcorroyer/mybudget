<?php

declare(strict_types=1);

namespace TheoD\MusicAutoTagger\Docker;

use function Castor\context;

class DockerUtils
{
    public static function isRunningInsideContainer(bool $throw = false): bool
    {
        $isRunningInsideContainer = file_exists('/.dockerenv');

        if ($throw && $isRunningInsideContainer) {
            throw new \RuntimeException('This command cannot be run inside a container.');
        }

        return $isRunningInsideContainer;
    }

    public static function isContainersRunning(array $containers): bool
    {
        $runningContainers = explode(\PHP_EOL, shell_exec('docker ps --format "{{.Names}}"'));
        foreach ($containers as $container) {
            if (! \in_array($container, $runningContainers, true)) {
                return false;
            }
        }

        return true;
    }

    public static function isImageExist(string $image): bool
    {
        return docker(context()->withAllowFailure()->withQuiet())
            ->add('image', 'inspect', $image)
            ->run()
            ->isSuccessful()
        ;
    }

    public static function isNetworkExist(string $network): bool
    {
        return docker(context()->withAllowFailure()->withQuiet())
            ->add('network', 'inspect', $network)
            ->run()
            ->isSuccessful()
        ;
    }
}
