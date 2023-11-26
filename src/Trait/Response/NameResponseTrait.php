<?php

declare(strict_types=1);

namespace App\Trait\Response;

use App\Serializable\SerializationGroups;
use Symfony\Component\Serializer\Annotation as Serializer;

trait NameResponseTrait
{
    #[Serializer\Groups([
        SerializationGroups::INCOME_CREATE,
        SerializationGroups::INCOME_UPDATE,
        SerializationGroups::EXPENSE_CREATE,
        SerializationGroups::EXPENSE_UPDATE,
        SerializationGroups::EXPENSE_CATEGORY_UPDATE,
    ])]
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
