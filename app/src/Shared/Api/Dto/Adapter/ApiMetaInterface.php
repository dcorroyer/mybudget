<?php

declare(strict_types=1);

namespace App\Shared\Api\Dto\Adapter;

use App\Shared\Api\Dto\Meta\PaginationMeta;
use Symfony\Component\Serializer\Attribute\DiscriminatorMap;

/**
 * TODO: See how to improve this to permit those who use this interface to use the discriminator map independently of this interface.
 */
#[DiscriminatorMap(
    typeProperty: 'type',
    mapping: [
        'pagination' => PaginationMeta::class,
    ]
)]
interface ApiMetaInterface
{
}
