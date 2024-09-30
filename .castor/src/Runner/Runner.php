<?php

declare(strict_types=1);

namespace TheoD\MusicAutoTagger\Runner;

use Castor\Context;
use Symfony\Component\Process\Process;
use TheoD\MusicAutoTagger\Docker\ContainerDefinition;
use TheoD\MusicAutoTagger\Docker\DockerUtils;

use function Castor\context;
use function Castor\run;

class Runner
{
    protected array $commands = [];
    protected array $envs = [];
    protected array $dockerEnvs = [];
    protected bool $dockerInteractive = true;
    protected bool $dockerTty = true;
    protected string $dockerShell = 'bash';
    protected Context $context;

    public function __construct(
        ?Context $context = null,
        private ?ContainerDefinition $containerDefinition = null,
        private readonly bool $preventRunningUsingDocker = false,
    ) {
        $this->context = $context ?? context();
    }

    public function withContainerDefinition(ContainerDefinition $containerDefinition): self
    {
        $this->containerDefinition = $containerDefinition;

        return $this;
    }

    /**
     * Return the base command, e.g. 'composer', null if the command should be run without a base command.
     */
    protected function getBaseCommand(): string|array|null
    {
        return null;
    }

    /**
     * Use that for running anything before the command is executed (e.g. setting environment variables, some checks, etc.).
     */
    protected function preRunCommand(): void
    {
    }

    protected function postRunCommand(Process $process): void
    {
    }

    /**
     * @internal
     */
    protected function mergeCommands(mixed ...$commands): string
    {
        $commands = array_filter($commands);

        if ($this->envs !== []) {
            $envs = array_map(static fn ($key, $value) => "{$key}={$value}", array_keys($this->envs), $this->envs);
            $commands = array_merge($envs, $commands);
        }

        $commandsAsArrays = array_map(
            callback: static fn ($command) => \is_array($command) ? $command : explode(' ', $command),
            array: $commands
        );
        $flattened = array_reduce(
            array: $commandsAsArrays,
            callback: static fn ($carry, $item) => [...$carry, ...$item],
            initial: []
        );

        return implode(' ', $flattened);
    }

    public function add(string|int|float ...$commands): static
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
    public function addIf(mixed $condition, ?string $key = null, string|array|null $value = null): static
    {
        if ($condition !== false && $condition !== null) {
            if ($key === null) {
                $this->commands[] = \is_array($value) ? implode(' ', $value) : $value;
            } elseif ($value === null) {
                $this->commands[] = $key;
            } elseif (\is_array($value)) {
                $this->commands[] = $key . ' ' . implode(' ' . $key . ' ', $value);
            } else {
                $this->commands[] = $key . ' ' . $value;
            }
        }

        return $this;
    }

    /**
     * Add environment variables.
     *
     * Usage:
     *
     * $this->addEnv('APP_ENV', 'dev');
     * $this->addEnv('DATABASE_URL', 'mysql://ci:ci@mysql:3306/ci', getenv('CI') === 'true');
     */
    public function addEnv(string $key, string $value, ?bool $if = true): static
    {
        if ($if) {
            $this->envs[$key] = $value;
        }

        return $this;
    }

    /**
     * Add environment variables for Docker.
     *
     * Usage:
     * $this->addDockerEnv('APP_ENV', 'dev');
     * $this->addDockerEnv('DATABASE_URL', 'mysql://ci:ci@mysql:3306/ci', getenv('CI') === 'true');
     */
    public function addDockerEnv(string $key, string $value, ?bool $if = true): static
    {
        if ($if) {
            $this->dockerEnvs[$key] = $value;
        }

        return $this;
    }

    /**
     * @param bool $block   if true the command will be dumped and the script will be stopped, otherwise the command will be dumped and the script will continue
     * @param bool $inlined If true, the output will be dumped as a single string, otherwise it will be dumped as an array (raw commands)
     */
    public function debug(bool $block = true, bool $inlined = true): static
    {
        $commands = $this->mergeCommands(
            $this->shouldRunUsingDocker() ? $this->doBuildDockerCommand() : [
                $this->getBaseCommand(),
                ...$this->commands,
            ]
        );
        if ($inlined) {
            $block ? dd($commands) : dump($commands);
        }

        $block ? dd(explode(' ', $commands)) : dump(explode(' ', $commands));

        return $this;
    }

    private function shouldRunUsingDocker(): bool
    {
        return DockerUtils::isRunningInsideContainer() === false && $this->isRunningUsingDockerAllowed();
    }

    public function run(?Context $context = null): Process
    {
        if ($context) {
            $oldContext = $this->context;
            $this->context = $context;
        }

        $this->preRunCommand();
        if ($this->shouldRunUsingDocker()) {
            $process = $this->runCommandInsideContainer();

            $this->postRunCommand($process);

            if ($context) {
                $this->context = $oldContext;
            }

            return $process;
        }

        $commands = $this->mergeCommands($this->getBaseCommand(), $this->commands);
        $this->commands = [];
        $process = run($commands, context: $this->context);

        $this->postRunCommand($process);

        if ($context) {
            $this->context = $oldContext;
        }

        return $process;
    }

    public function isRunningUsingDockerAllowed(): bool
    {
        return ! $this->preventRunningUsingDocker;
    }

    protected function buildDockerCommand(array $baseCommands, ContainerDefinition $containerDefinition): array
    {
        $dockerEnvsRaw = [...$containerDefinition->envs, ...$this->dockerEnvs];
        $dockerEnvs = [];
        foreach ($dockerEnvsRaw as $key => $value) {
            $dockerEnvs[] = '-e';
            $dockerEnvs[] = "{$key}=\"{$value}\"";
        }

        $this->commands = [
            'docker',
            'exec',
            $this->isDockerInteractive() ? '-i' : '',
            $this->isDockerTty() ? '-t' : '',
            ...$dockerEnvs,
            \sprintf('--user="%s"', $containerDefinition->user),
            \sprintf('-w "%s"', $containerDefinition->workingDirectory),
            $containerDefinition->name,
            $this->dockerShell,
            '-c',
            \sprintf('"%s"', implode(' ', [...$baseCommands, ...$this->commands])),
        ];

        return $this->commands;
    }

    private function doBuildDockerCommand(): array
    {
        $containerDefinition = $this->containerDefinition ?? $this->defaultContainerDefinition();
        $baseCommand = \is_array($this->getBaseCommand()) ? $this->getBaseCommand() : [$this->getBaseCommand() ?? ''];

        return $this->buildDockerCommand($baseCommand, $containerDefinition);
    }

    public function defaultContainerDefinition(): ContainerDefinition
    {
        $className = $this::class;

        return $this->containerDefinition ?? throw new \RuntimeException(
            'Container definition is not set. Please set it using defaultContainerDefinition() method or by calling withContainerDefinition() method. Class: ' . $className
        );
    }

    protected function runCommandInsideContainer(): Process
    {
        $commands = $this->mergeCommands($this->doBuildDockerCommand());
        $this->commands = [];

        return run($commands, context: $this->context);
    }

    public function withDockerInteractive(bool $dockerInteractive = true): static
    {
        $this->dockerInteractive = $dockerInteractive;

        return $this;
    }

    public function isDockerInteractive(): bool
    {
        if ($this->context->quiet) {
            return false;
        }

        return $this->dockerInteractive;
    }

    public function withDockerTty(bool $dockerTty = true): static
    {
        $this->dockerTty = $dockerTty;

        return $this;
    }

    public function isDockerTty(): bool
    {
        return $this->dockerTty;
    }

    /**
     * @param string $dockerShell e.g. 'bash', 'sh', 'zsh', etc.
     */
    public function withDockerShell(string $dockerShell): static
    {
        $this->dockerShell = $dockerShell;

        return $this;
    }

    public static function new(ContainerDefinition $containerDefinition, ?Context $context = null): static
    {
        return new static(context: $context, containerDefinition: $containerDefinition);
    }
}
