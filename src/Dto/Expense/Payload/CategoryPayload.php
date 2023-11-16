<?php

declare(strict_types=1);

namespace App\Dto\Expense\Payload;

use App\Serializable\SerializationGroups;
use My\RestBundle\Contract\PayloadInterface;
use Symfony\Component\Serializer\Annotation as Serializer;

class CategoryPayload implements PayloadInterface
{
    #[Serializer\Groups([SerializationGroups::EXPENSE_CREATE, SerializationGroups::EXPENSE_UPDATE])]
    private ?int $id = null;

    #[Serializer\Groups([SerializationGroups::EXPENSE_CREATE, SerializationGroups::EXPENSE_UPDATE])]
    private string $name;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): self
    {
        $this->id = $id;

        return $this;
    }

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
