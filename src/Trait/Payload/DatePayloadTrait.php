<?php

declare(strict_types=1);

namespace App\Trait\Payload;

use App\Serializable\SerializationGroups;
use Symfony\Component\Serializer\Annotation as Serializer;
use Symfony\Component\Serializer\Annotation\Context;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;

trait DatePayloadTrait
{
    #[Serializer\Groups([
        SerializationGroups::INCOME_CREATE,
        SerializationGroups::INCOME_UPDATE,
        SerializationGroups::EXPENSE_CREATE,
        SerializationGroups::EXPENSE_UPDATE,
    ])]
    #[Context(
        normalizationContext: [
            DateTimeNormalizer::FORMAT_KEY => 'Y-m-d',
        ],
        denormalizationContext: [
            DateTimeNormalizer::FORMAT_KEY => 'Y-m-d',
        ],
    )]
    private \DateTimeInterface $date;

    public function getDate(): \DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }
}
