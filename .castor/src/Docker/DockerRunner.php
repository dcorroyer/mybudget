<?php

declare(strict_types=1);

namespace dcorroyer\mybudget\Docker;

use Castor\Context;
use dcorroyer\mybudget\Runner\Runner;

class DockerRunner extends Runner
{
    public function __construct(
        ?Context $context = null
    ) {
        parent::__construct(context: $context, preventRunningUsingDocker: true);
    }

    protected function getBaseCommand(): string
    {
        return 'docker';
    }

    public function compose(string|int ...$args): static
    {
        return $this->add('compose')->add(...$args);
    }
}
