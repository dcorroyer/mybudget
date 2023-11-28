<?php

declare(strict_types=1);

namespace App\Trait\Payload;

use App\Serializable\SerializationGroups;
use Symfony\Component\Serializer\Annotation as Serializer;
use Symfony\Component\Serializer\Annotation\Context;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;

trait DatePayloadTrait
{
    #[Serializer\Groups([SerializationGroups::TRACKING_CREATE])]
    #[Context(
        normalizationContext: [
            DateTimeNormalizer::FORMAT_KEY => 'Y-m',
        ],
        denormalizationContext: [
            DateTimeNormalizer::FORMAT_KEY => 'Y-m',
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
