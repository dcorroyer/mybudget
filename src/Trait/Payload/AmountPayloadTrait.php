<?php

declare(strict_types=1);

namespace App\Trait\Payload;

trait AmountPayloadTrait
{
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
