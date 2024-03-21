<?php

declare(strict_types=1);

namespace App\Dto\Expense\Payload;

use App\Trait\Payload\AmountPayloadTrait;
use App\Trait\Payload\IdPayloadTrait;
use App\Trait\Payload\NamePayloadTrait;
use My\RestBundle\Contract\PayloadInterface;

class ExpenseLinePayload implements PayloadInterface
{
    use AmountPayloadTrait;
    use IdPayloadTrait;
    use NamePayloadTrait;
}
