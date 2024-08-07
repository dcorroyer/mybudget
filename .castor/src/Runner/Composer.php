<?php

declare(strict_types=1);

namespace dcorroyer\mybudget\Runner;

use Castor\Context;
use dcorroyer\mybudget\ContainerDefinitionBag;
use dcorroyer\mybudget\Docker\ContainerDefinition;

class Composer extends Runner
{
    public function __construct(
        ?Context $context = null,
        ?ContainerDefinition $containerDefinition = null,
        readonly bool $preventRunningUsingDocker = false,
    ) {
        parent::__construct(
            context: $context,
            containerDefinition: $containerDefinition ?? ContainerDefinitionBag::php(),
            preventRunningUsingDocker: $preventRunningUsingDocker
        );
    }

    protected function getBaseCommand(): ?string
    {
        return 'composer';
    }

    public function install(string|int ...$args): static
    {
        return $this->add('install', ...$args);
    }

    public function update(string|int ...$args): static
    {
        return $this->add('update', ...$args);
    }
}

function composer(?Context $context = null, ?string $workingDirectory = null): Composer
{
    return new Composer($context, $workingDirectory);
}
