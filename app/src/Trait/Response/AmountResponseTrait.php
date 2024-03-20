<?php

declare(strict_types=1);

namespace App\Trait\Response;

use App\Serializable\SerializationGroups;
use Symfony\Component\Serializer\Annotation as Serializer;

trait AmountResponseTrait
{
    #[Serializer\Groups([
        SerializationGroups::INCOME_CREATE,
        SerializationGroups::INCOME_UPDATE,
        SerializationGroups::EXPENSE_CREATE,
        SerializationGroups::EXPENSE_UPDATE,
    ])]
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
