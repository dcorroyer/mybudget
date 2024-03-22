<?php

declare(strict_types=1);

namespace App\Trait\Response;

use App\Serializable\SerializationGroups;
use Symfony\Component\Serializer\Annotation as Serializer;

trait IdResponseTrait
{
    #[Serializer\Groups([
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
