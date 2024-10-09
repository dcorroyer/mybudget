<?php

declare(strict_types=1);

namespace App\Dto\ExpenseCategory\Payload;

use Doctrine\DBAL\Types\Types;
use Symfony\Component\Validator\Constraints as Assert;

class ExpenseCategoryPayload
{
    #[Assert\NotBlank]
    #[Assert\Type(type: Types::STRING)]
    public string $name;
}
