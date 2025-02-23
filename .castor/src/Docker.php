<?php

declare(strict_types=1);

use Castor\CommandBuilder\CommandBuilderInterface;
use Castor\Context;
use Symfony\Component\Process\Process;

use function Castor\context;
use function Castor\fs;
use function Castor\io;
use function Castor\run;

class Docker implements CommandBuilderInterface
{
    public function isRunningInDocker(): bool
    {
        return fs()->exists('/.dockerenv');
    }

    public function preventRunningInsideDocker(): void
    {
        if ($this->isRunningInDocker()) {
            io()->error('This command should not be run inside the container.');

            exit(1);
        }
    }

    /**
     * @param array<string> $services
     *
     * @return array<string> The services that are not running
     */
    public function isServicesRun(array $services): array
    {
        $output = $this->add('compose', 'ps', '--services')->run(context()->withQuiet())->getOutput();

        $notRunning = [];

        foreach ($services as $serviceName) {
            if (! str_contains($output, $serviceName)) {
                $notRunning[] = $serviceName;
            }
        }

        return $notRunning;
    }

    /**
     * @param array<string> $commands
     */
    public function __construct(
        private array $commands = [],
    ) {
    }

    public function debug(bool $block = false): static
    {
        if ($block) {
            dd($this->getCommand());
        } else {
            dump($this->getCommand());
        }

        return $this;
    }

    /**
     * @return array<string>
     */
    public function getCommand(): array
    {
        if ($this->isRunningInDocker()) {
            return $this->commands;
        }

        $commands = ['docker'];

        return [...$commands, ...$this->commands];
    }

    public function add(string ...$args): static
    {
        $this->commands = [...$this->commands, ...$args];

        return $this;
    }

    public function addIf(bool $condition, string ...$args): static
    {
        if ($condition) {
            $this->commands = [...$this->commands, ...$args];
        }

        return $this;
    }

    public function run(?Context $context = null): Process
    {
        $context = $context ?? new Context();
        $context = $context->withAllowFailure()->withTty();

        return run($this, context: $context);
    }

    public function hasImages(array $images): bool
    {
        $output = $this->add('compose', 'images')->run(context()->withQuiet())->getOutput();

        foreach ($images as $image) {
            if (! str_contains($output, $image)) {
                return false;
            }
        }

        return true;
    }
}
