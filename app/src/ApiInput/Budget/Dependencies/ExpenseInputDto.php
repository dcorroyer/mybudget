<?php

declare(strict_types=1);

namespace App\ApiInput\Budget\Dependencies;

use Doctrine\DBAL\Types\Types;
use Symfony\Component\Validator\Constraints as Assert;

class ExpenseInputDto
{
    #[Assert\Type(Types::STRING)]
    public string $name;

    #[Assert\Type(type: Types::FLOAT)]
    public float $amount;

    #[Assert\NotBlank]
    public ?ExpenseCategoryInputDto $category = null;
}
