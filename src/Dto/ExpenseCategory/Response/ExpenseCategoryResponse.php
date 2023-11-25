<?php

declare(strict_types=1);

namespace App\Dto\ExpenseCategory\Response;

use App\Trait\Response\IdResponseTrait;
use App\Trait\Response\NameResponseTrait;
use My\RestBundle\Contract\ResponseInterface;

class ExpenseCategoryResponse implements ResponseInterface
{
    use IdResponseTrait;
    use NameResponseTrait;
}
