<?php

declare(strict_types=1);

namespace App\Dto\Expense\Payload;

use App\Serializable\SerializationGroups;
use My\RestBundle\Contract\PayloadInterface;
use Symfony\Component\Serializer\Annotation as Serializer;

class ExpenseLinePayload implements PayloadInterface
{
    #[Serializer\Groups([SerializationGroups::EXPENSE_CREATE, SerializationGroups::EXPENSE_UPDATE])]
    private string $name;

    #[Serializer\Groups([SerializationGroups::EXPENSE_CREATE, SerializationGroups::EXPENSE_UPDATE])]
    private float $amount;

    //    #[Serializer\Groups([SerializationGroups::EXPENSE_CREATE, SerializationGroups::EXPENSE_UPDATE])]
    //    private CategoryPayload $category;

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getAmount(): float
    {
        return $this->amount;
    }

    public function setAmount(float $amount): self
    {
        $this->amount = $amount;

        return $this;
    }

    //    public function getCategory(): CategoryPayload
    //    {
    //        return $this->category;
    //    }
    //
    //    public function setCategory(CategoryPayload $category): self
    //    {
    //        $this->category = $category;
    //
    //        return $this;
    //    }
}
