<?php

declare(strict_types=1);

use Castor\Context;
use Symfony\Component\Process\Process;

use function Castor\context;

class Node
{
    use RunnerTrait {
        __construct as private __runnerTraitConstruct;
    }

    public function __construct(
        private readonly Context $context
    ) {
        $this->__runnerTraitConstruct($context);
    }

    public function install(): Process
    {
        return $this->add('install')->runCommand();
    }

    private function getBaseCommand(): string
    {
        return 'npm';
    }

    protected function allowRunningUsingDocker(): bool
    {
        return true;
    }

    public function run(string $command): Process
    {
        $this->add('run');

        return $this->add($command)->runCommand();
    }
}

function node(?Context $context = null): Node
{
    return new Node($context ?? context());
}
