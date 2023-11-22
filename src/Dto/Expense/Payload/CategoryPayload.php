<?php

declare(strict_types=1);

namespace App\Dto\Expense\Payload;

use App\Trait\Payload\IdPayloadTrait;
use App\Trait\Payload\NamePayloadTrait;
use My\RestBundle\Contract\PayloadInterface;

class CategoryPayload implements PayloadInterface
{
    use IdPayloadTrait;
    use NamePayloadTrait;
}
