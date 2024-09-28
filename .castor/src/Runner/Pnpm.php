<?php

declare(strict_types=1);

namespace TheoD\MusicAutoTagger\Runner;

use Castor\Context;
use TheoD\MusicAutoTagger\ContainerDefinitionBag;
use TheoD\MusicAutoTagger\Docker\ContainerDefinition;

use function Castor\io;
use function TheoD\MusicAutoTagger\app_context;

class Pnpm extends Runner
{
    public function __construct(
        ?Context $context = null,
        ?ContainerDefinition $containerDefinition = null,
        bool $preventRunningUsingDocker = false,
    ) {
        if ($this->hasPackageJson() === false) {
            io()->note('No package.json or yarn.lock file found in the working directory');
        }

        parent::__construct(
            context: $context,
            containerDefinition: $containerDefinition ?? ContainerDefinitionBag::node(),
            preventRunningUsingDocker: $preventRunningUsingDocker
        );
    }

    public function hasPackageJson(): bool
    {
        return is_file(app_context()->workingDirectory . '/package.json');
    }

    protected function getBaseCommand(): ?string
    {
        return 'pnpm';
    }

    public function install(string|int ...$args): static
    {
        return $this->add('install', ...$args);
    }
}

function pnpm(?Context $context = null): Pnpm
{
    return new Pnpm($context);
}
