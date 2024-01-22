<?php

declare(strict_types=1);

namespace App\Trait\Payload;

trait NamePayloadTrait
{
    private string $name;

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }
}
