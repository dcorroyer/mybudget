<?php

declare(strict_types=1);

namespace TheoD\MusicAutoTagger\Docker;

class ContainerDefinition
{
    public function __construct(
        public string $composeName,
        public string $name,
        public string $workingDirectory,
        public ?string $user = null,
        public array $envs = [],
    ) {
    }

    public function withEnv(string $name, string $value): static
    {
        $this->envs[$name] = $value;

        return $this;
    }

    public function withUser(string $user): static
    {
        $this->user = $user;

        return $this;
    }

    public function withWorkingDirectory(string $workingDirectory): static
    {
        $this->workingDirectory = $workingDirectory;

        return $this;
    }

    public function withComposeName(string $composeName): static
    {
        $this->composeName = $composeName;

        return $this;
    }

    public function withName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getEnv(): array
    {
        return $this->envs;
    }

    public function getUser(): ?string
    {
        return $this->user;
    }

    public function getWorkingDirectory(): string
    {
        return $this->workingDirectory;
    }

    public function getComposeName(): string
    {
        return $this->composeName;
    }

    public function getName(): string
    {
        return $this->name;
    }
}
