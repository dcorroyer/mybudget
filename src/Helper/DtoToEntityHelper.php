<?php

declare(strict_types=1);

namespace App\Helper;

use My\RestBundle\Contract\PayloadInterface;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class DtoToEntityHelper
{
    public function __construct(
        private readonly NormalizerInterface $normalizer,
        private readonly DenormalizerInterface $denormalizer
    ) {
    }

    public function create(PayloadInterface $payload, object $entity): object
    {
        $data = $this->normalizer->normalize($payload, 'json');

        return $this->denormalizer->denormalize($data, $entity::class, 'object');
    }

    public function update(PayloadInterface $payload, object $entity): object
    {
        $data = $this->normalizer->normalize($payload, 'json');

        $this->denormalizer->denormalize(
            $data,
            $entity::class,
            'object',
            [
                AbstractNormalizer::OBJECT_TO_POPULATE => $entity,
            ]
        );

        return $entity;
    }
}
