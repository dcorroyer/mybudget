<?php

declare(strict_types=1);

namespace App\Dto\Income\Payload;

use App\Contract\PayloadInterface;
use App\Enum\IncomeTypes;
use App\Serializable\SerializationGroups;
use Symfony\Component\Serializer\Annotation as Serializer;
use Symfony\Component\Validator\Constraints as Assert;

class IncomePayload implements PayloadInterface
{
    #[Serializer\Groups([SerializationGroups::INCOME_CREATE, SerializationGroups::INCOME_UPDATE])]
    #[Assert\NotBlank]
    private string $name;

    #[Serializer\Groups([SerializationGroups::INCOME_CREATE, SerializationGroups::INCOME_UPDATE])]
    #[Assert\NotBlank]
    private float $amount = 0;

    #[Serializer\Groups([SerializationGroups::INCOME_CREATE, SerializationGroups::INCOME_UPDATE])]
    #[Assert\NotBlank]
    #[Assert\Date]
    private string $date;

    #[Serializer\Groups([SerializationGroups::INCOME_CREATE, SerializationGroups::INCOME_UPDATE])]
    #[Assert\NotBlank]
    private IncomeTypes $type;

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

    public function getDate(): string
    {
        return $this->date;
    }

    public function setDate(string $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getType(): IncomeTypes
    {
        return $this->type;
    }

    public function setType(IncomeTypes $type): self
    {
        $this->type = $type;

        return $this;
    }
}
