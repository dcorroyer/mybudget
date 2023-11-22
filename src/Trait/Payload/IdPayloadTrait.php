<?php

declare(strict_types=1);

namespace App\Trait\Payload;

use App\Serializable\SerializationGroups;
use Symfony\Component\Serializer\Annotation as Serializer;

trait IdPayloadTrait
{
    #[Serializer\Groups([SerializationGroups::EXPENSE_CREATE, SerializationGroups::EXPENSE_UPDATE])]
    private ?int $id = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): self
    {
        $this->id = $id;

        return $this;
    }
}
