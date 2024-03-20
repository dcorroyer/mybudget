<?php

declare(strict_types=1);

namespace App\Trait\Payload;

use Doctrine\DBAL\Types\Types;
use Symfony\Component\Validator\Constraints as Assert;

trait AmountPayloadTrait
{
    #[Assert\Type(Types::FLOAT)]
    private float $amount;

    public function getAmount(): float
    {
        return $this->amount;
    }

    public function setAmount(float $amount): self
    {
        $this->amount = $amount;

        return $this;
    }
}
