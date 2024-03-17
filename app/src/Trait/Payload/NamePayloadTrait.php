<?php

declare(strict_types=1);

namespace App\Trait\Payload;

use Doctrine\DBAL\Types\Types;
use Symfony\Component\Validator\Constraints as Assert;

trait NamePayloadTrait
{
    #[Assert\Type(Types::STRING)]
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
