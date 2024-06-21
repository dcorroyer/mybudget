<?php

declare(strict_types=1);

namespace dcorroyer\mybudget;

use dcorroyer\mybudget\Docker\ContainerDefinition;

class ContainerDefinitionBag
{
    public static function php(): ContainerDefinition
    {
        return new ContainerDefinition(composeName: 'app', name: 'mybudget-app-1', workingDirectory: '/app', user: 'www-data');
    }

    public static function tools(?string $toolName = null): ContainerDefinition
    {
        if ($toolName === null) {
            return self::php()->withWorkingDirectory('/tools');
        }

        return self::php()->withWorkingDirectory("/tools/{$toolName}");
    }

    public static function node(): ContainerDefinition
    {
        return new ContainerDefinition(composeName: 'app', name: 'mybudget-app-1', workingDirectory: '/app', user: 'www-data');
    }
}
