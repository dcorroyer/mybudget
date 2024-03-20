<?php

declare(strict_types=1);

namespace App\Trait\Response;

use App\Serializable\SerializationGroups;
use Symfony\Component\Serializer\Annotation as Serializer;

trait IdResponseTrait
{
    #[Serializer\Groups([
        SerializationGroups::INCOME_CREATE,
        SerializationGroups::INCOME_UPDATE,
        SerializationGroups::EXPENSE_CREATE,
        SerializationGroups::EXPENSE_UPDATE,
        SerializationGroups::EXPENSE_CATEGORY_UPDATE,
        SerializationGroups::USER_CREATE,
        SerializationGroups::USER_GET,
        SerializationGroups::BUDGET_CREATE,
        SerializationGroups::BUDGET_UPDATE,
    ])]
    private int $id;

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): self
    {
        $this->id = $id;

        return $this;
    }
}
