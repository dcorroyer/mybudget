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
    private ?ExpenseLineCategoryPayload $category;

    public function getCategory(): ?ExpenseLineCategoryPayload
    {
        return $this->category;
    }

    public function setCategory(?ExpenseLineCategoryPayload $category): self
    {
        $this->category = $category;

        return $this;
    }
}
