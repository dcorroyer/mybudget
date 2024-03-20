<?php

declare(strict_types=1);

namespace App\Dto\Budget\Payload;

use App\Trait\Payload\DatePayloadTrait;
use My\RestBundle\Contract\PayloadInterface;

class UpdateBudgetPayload implements PayloadInterface
{
    use DatePayloadTrait;
}
