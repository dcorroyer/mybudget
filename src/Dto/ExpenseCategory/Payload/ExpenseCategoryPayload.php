<?php

declare(strict_types=1);

namespace App\Dto\ExpenseCategory\Payload;

use App\Trait\Payload\IdPayloadTrait;
use App\Trait\Payload\NamePayloadTrait;
use My\RestBundle\Contract\PayloadInterface;

class ExpenseCategoryPayload implements PayloadInterface
{
    use IdPayloadTrait;
    use NamePayloadTrait;
}
