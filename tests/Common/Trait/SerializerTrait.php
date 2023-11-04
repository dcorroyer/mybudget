<?php

declare(strict_types=1);

namespace App\Tests\Common\Trait;

use PHPUnit\Framework\Attributes\Before;
use Symfony\Component\PropertyInfo\Extractor\PhpDocExtractor;
use Symfony\Component\PropertyInfo\Extractor\ReflectionExtractor;
use Symfony\Component\PropertyInfo\PropertyInfoExtractor;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ArrayDenormalizer;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\SerializerInterface;

trait SerializerTrait
{
    private SerializerInterface $serializer;

    private ObjectNormalizer $normalizer;

    #[Before]
    public function setUpSerializerBeforeTest(): void
    {
        $encoder = [new JsonEncoder()];
        $extractor = new PropertyInfoExtractor([], [new PhpDocExtractor(), new ReflectionExtractor()]);
        $normalizer = [
            new DateTimeNormalizer(),
            new ArrayDenormalizer(),
            new ObjectNormalizer(propertyTypeExtractor: $extractor),
        ];
        $this->normalizer = new ObjectNormalizer(propertyTypeExtractor: $extractor);
        $this->serializer = new Serializer($normalizer, $encoder);
    }
}
