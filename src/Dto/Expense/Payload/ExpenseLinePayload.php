<?php

declare(strict_types=1);

namespace App\Dto\Expense\Payload;

use App\Serializable\SerializationGroups;
use App\Trait\Payload\AmountPayloadTrait;
use App\Trait\Payload\IdPayloadTrait;
use App\Trait\Payload\NamePayloadTrait;
use My\RestBundle\Contract\PayloadInterface;
use Symfony\Component\Serializer\Annotation as Serializer;

class ExpenseLinePayload implements PayloadInterface
{
    use IdPayloadTrait;
    use NamePayloadTrait;
    use AmountPayloadTrait;

    #[Serializer\Groups([SerializationGroups::EXPENSE_CREATE, SerializationGroups::EXPENSE_UPDATE])]
    private ?ExpenseCategoryPayload $category;

    public function getCategory(): ?ExpenseCategoryPayload
    {
        return $this->category;
    }

    public function setCategory(?ExpenseCategoryPayload $category): self
    {
        $this->category = $category;

        return $this;
    }
}
