<?php

declare(strict_types=1);

namespace App\Dto\Income\Response;

use App\Contract\PayloadInterface;
use App\Enum\IncomeTypes;
use App\Serializable\SerializationGroups;
use Symfony\Component\Serializer\Annotation as Serializer;
use Symfony\Component\Serializer\Annotation\Context;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;

class IncomeResponse implements PayloadInterface
{
    #[Serializer\Groups([SerializationGroups::INCOME_CREATE, SerializationGroups::INCOME_UPDATE])]
    private int $id;

    #[Serializer\Groups([SerializationGroups::INCOME_CREATE, SerializationGroups::INCOME_UPDATE])]
    private string $name;

    #[Serializer\Groups([SerializationGroups::INCOME_CREATE, SerializationGroups::INCOME_UPDATE])]
    private float $amount = 0;

    #[Context(
        normalizationContext: [
            DateTimeNormalizer::FORMAT_KEY => 'Y-m-d',
        ],
        denormalizationContext: [
            DateTimeNormalizer::FORMAT_KEY => 'Y-m-d',
        ],
    )]
    #[Serializer\Groups([SerializationGroups::INCOME_CREATE, SerializationGroups::INCOME_UPDATE])]
    private \DateTimeInterface $date;

    #[Serializer\Groups([SerializationGroups::INCOME_CREATE, SerializationGroups::INCOME_UPDATE])]
    private IncomeTypes $type;

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): self
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

    public function getAmount(): float
    {
        return $this->amount;
    }

    public function setAmount(float $amount): self
    {
        $this->amount = $amount;

        return $this;
    }

    public function getDate(): \DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
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
