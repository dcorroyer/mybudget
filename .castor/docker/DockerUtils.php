<?php

declare(strict_types=1);

namespace Castor\Utils\Docker;

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
}
