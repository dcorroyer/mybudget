<?php

declare(strict_types=1);

namespace App\Dto\Budget\Payload\Dependencies;

use Doctrine\DBAL\Types\Types;
use Symfony\Component\Validator\Constraints as Assert;

class ExpensePayload
{
    #[Assert\NotBlank]
    #[Assert\Type(type: Types::STRING)]
    public string $name;

    #[Assert\NotBlank]
    #[Assert\Type(type: Types::FLOAT)]
    public float $amount;

    #[Assert\NotBlank]
    #[Assert\Type(type: Types::STRING)]
    public string $category;

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function setAmount(float $amount): static
    {
        $this->amount = $amount;

        return $this;
    }

    public function setCategory(string $category): static
    {
        $this->category = $category;

        return $this;
    }
}
