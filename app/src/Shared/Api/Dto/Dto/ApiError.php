<?php

declare(strict_types=1);

namespace App\Shared\Api\Dto\Dto;

use Symfony\Component\Serializer\Attribute\DiscriminatorMap;

/**
 * TODO: See how to improve this to permit those who use this interface to use the discriminator map independently of this class.
 */
#[DiscriminatorMap(
    typeProperty: 'type',
    mapping: [
        'field_api_error' => FieldApiError::class,
    ]
)]
class ApiError
{
    public function __construct(
        public string $message,
        public int $code,
    ) {
    }
}
