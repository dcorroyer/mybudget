<?php

declare(strict_types=1);

use Castor\Context;
use Castor\Utils\Docker\CastorDockerContext;
use Castor\Utils\Docker\DockerUtils;
use Symfony\Component\Process\Process;

use function Castor\context;
use function Castor\io;
use function Castor\run;
use function Castor\Utils\Docker\docker;

trait RunnerTrait
{
    private array $commands = [];

    public function __construct(
        private readonly Context $castorContext
    ) {
    }

    /**
     * Return the base command, e.g. 'composer', null if the command should be run without a base command.
     */
    protected function getBaseCommand(): ?string
    {
        return null;
    }

    /**
     * Return true if the command should be run with Docker.
     *
     * If true, the method `withDockerContext` should be implemented if you want use custom docker context.
     * Otherwise, the default docker context will be used from the context passed in the constructor if it exists.
     * If the default docker context does not exist, an error will be thrown.
     */
    abstract protected function allowRunningUsingDocker(): bool;

    /**
     * This configuration is global and have priority over the docker context configuration.
     *
     * @return ?bool if null, the configuration from the docker context will be used
     */
    protected function allowRunningInsideContainer(): ?bool
    {
        return null;
    }

    /**
     * Use that for running anything before the command is executed (e.g. setting environment variables, some checks, etc.).
     */
    protected function preRunCommand(): void
    {
    }

    /**
     * @internal
     */
    private function getDockerContext(): CastorDockerContext
    {
        if ($this->allowRunningUsingDocker() === false) {
            throw new RuntimeException('This command should be only called when running with Docker.');
        }

        return $this->withDockerContext();
    }

    /**
     * By default it will search for a docker context corresponding to the base command name in the context passed in the constructor.
     * If no base command is defined, it will search for a docker context named 'default'.
     *
     * If any of the above is not found, an error will be thrown.
     * In that case, you can override this method and return a specific CastorDockerContext or
     * define your docker context within the context passed in the constructor.
     */
    protected function withDockerContext(): CastorDockerContext
    {
        /** @var ?array<string, CastorDockerContext> $docker */
        $docker = $this->castorContext->data['docker'] ?? null;

        if ($docker === null) {
            throw new RuntimeException(
                'A array of CastorDockerContext is required to run this command outside a container.'
            );
        }

        $dockerContext = $docker[$this->getBaseCommand()] ?? $docker['default'] ?? null;

        if ($dockerContext === null) {
            io()->error([
                "DockerContext is required in the context '{$this->castorContext->name}'",
                "data: ['docker' => ['{$this->getBaseCommand()}' => new DockerContext()]]",
                'If you don\'t want to use docker context from the context, you can override the method "withDockerContext" and return a new specific DockerContext.',
            ]);
            exit(1);
        }

        return $dockerContext;
    }

    /**
     * @internal
     */
    private function mergeCommands(mixed ...$commands): string
    {
        $commands = array_filter($commands);

        $commandsAsArrays = array_map(
            callback: static fn ($command) => is_array($command) ? $command : explode(' ', $command),
            array: $commands
        );
        $flattened = array_reduce(
            array: $commandsAsArrays,
            callback: static fn ($carry, $item) => [...$carry, ...$item],
            initial: []
        );

        return implode(' ', $flattened);
    }

    /**
     * Add parts of the command.
     *
     * Usage:
     *
     * Imagine you want to run `composer install --no-dev`:
     *
     * getBaseCommand() should return 'composer'
     *
     * $this->add('install', '--no-dev');
     *
     * @return RunnerTrait|Composer|QaTools|QaVendor|Symfony
     */
    public function add(string ...$commands): self
    {
        $this->commands = [...$this->commands, ...$commands];

        return $this;
    }

    /**
     * Add parts of the command only if the condition is true.
     *
     * Usage:
     *
     * Imagine you want to run `composer install --no-dev` only if the $noDev is true:
     *
     * getBaseCommand() should return 'composer'
     * $noDev = true;
     *
     * $this->add('install');
     * $this->addIf($noDev, '--no-dev');
     *
     * Will run: composer install --no-dev
     *
     * And if you want to add options with values:
     *
     * $this->addIf($noDev, '--no-dev', ['value1', 'value2']);
     *
     * Will run: composer install --no-dev value1 value2
     *
     * And if you want to add options with values and keys:
     *
     * $this->addIf($noDev, null, ['--option1', '--option2']);
     *
     * Will run: composer install --option1 --option2
     */
    public function addIf(mixed $condition, ?string $key = null, string|array|null $value = null): void
    {
        if ($condition !== false && $condition !== null) {
            if ($key === null) {
                $this->commands[] = is_array($value) ? implode(' ', $value) : $value;
            } elseif ($value === null) {
                $this->commands[] = $key;
            } elseif (is_array($value)) {
                $this->commands[] = $key . ' ' . implode(' ' . $key . ' ', $value);
            } else {
                $this->commands[] = $key . ' ' . $value;
            }
        }
    }

    public function runCommand(): Process
    {
        $commands = $this->mergeCommands($this->getBaseCommand(), $this->commands);
        $this->commands = [];

        $isRunningInsideContainer = DockerUtils::isRunningInsideContainer();
        $dockerContext = null;
        if ($this->allowRunningUsingDocker()) {
            $dockerContext = $this->getDockerContext();
        }

        if ($isRunningInsideContainer === false && $dockerContext !== null) {
            $this->preRunCommand();

            return docker()->compose()->exec(
                service: $dockerContext->serviceName,
                args: [$commands],
                user: $dockerContext->user,
                workdir: $dockerContext->workdir
            );
        }

        if ($isRunningInsideContainer === true && $dockerContext->allowRunningInsideContainer === false) {
            $contextName = context()->name;
            $currentDockerContextName = $this->getBaseCommand() ?? 'default';
            io()->error([
                "Context '{$contextName}' with Docker context '{$currentDockerContextName}' does not allow running this command inside the container.",
                "Please run it outside the container or set 'allowRunningInsideContainer' to true in the docker context.",
                'Current context: ' . context()->name,
                'Command: ' . $commands,
            ]);
            exit(1);
        }

        $this->preRunCommand();

        return run($commands, context: $this->castorContext);
    }
}
