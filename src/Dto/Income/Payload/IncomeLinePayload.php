<?php

declare(strict_types=1);

namespace App\Dto\Income\Payload;

use App\Enum\IncomeTypes;
use App\Trait\Payload\AmountPayloadTrait;
use App\Trait\Payload\IdPayloadTrait;
use App\Trait\Payload\NamePayloadTrait;
use My\RestBundle\Contract\PayloadInterface;

class IncomeLinePayload implements PayloadInterface
{
    use IdPayloadTrait;
    use NamePayloadTrait;
    use AmountPayloadTrait;

    private IncomeTypes $type;

    public function getType(): IncomeTypes
    {
        return $this->type;
    }

    public function setType(IncomeTypes $type): self
    {
        $this->type = $type;

        return $this;
    }
}
