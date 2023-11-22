<?php

declare(strict_types=1);

namespace App\Dto\Expense\Response;

use App\Trait\Response\IdResponseTrait;
use App\Trait\Response\NameResponseTrait;
use My\RestBundle\Contract\ResponseInterface;

class CategoryResponse implements ResponseInterface
{
    use IdResponseTrait;
    use NameResponseTrait;
}
