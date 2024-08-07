<?php

declare(strict_types=1);

namespace dcorroyer\mybudget\Runner;

use Castor\Context;
use dcorroyer\mybudget\ContainerDefinitionBag;
use dcorroyer\mybudget\Docker\ContainerDefinition;

use function Castor\io;
use function dcorroyer\mybudget\app_context;

class Npm extends Runner
{
    public function __construct(
        ?Context $context = null,
        ?ContainerDefinition $containerDefinition = null,
        bool $preventRunningUsingDocker = false,
    ) {
        if (
            ! is_file(app_context()->workingDirectory . '/package.json')
            && ! is_file(app_context()->workingDirectory . '/yarn.lock')
        ) {
            io()->warning('No package.json or yarn.lock file found in the working directory');
        }

        parent::__construct(
            context: $context,
            containerDefinition: $containerDefinition ?? ContainerDefinitionBag::node(),
            preventRunningUsingDocker: $preventRunningUsingDocker
        );
    }

    protected function getBaseCommand(): ?string
    {
        return 'npm';
    }

    public function install(string|int ...$args): static
    {
        return $this->add('install', ...$args);
    }
}

function npm(?Context $context = null): Npm
{
    return new Npm($context);
}
