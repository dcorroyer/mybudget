<?php

declare(strict_types=1);

namespace App\Dto\Income\Response;

use App\Trait\Response\AmountResponseTrait;
use App\Trait\Response\IdResponseTrait;
use App\Trait\Response\NameResponseTrait;
use My\RestBundle\Contract\ResponseInterface;

class IncomeResponse implements ResponseInterface
{
    use AmountResponseTrait;
    use IdResponseTrait;
    use NameResponseTrait;
}
