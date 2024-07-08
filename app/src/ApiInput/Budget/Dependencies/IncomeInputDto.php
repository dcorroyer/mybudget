<?php

declare(strict_types=1);

namespace App\ApiInput\Budget\Dependencies;

use Doctrine\DBAL\Types\Types;
use Symfony\Component\Validator\Constraints as Assert;

class IncomeInputDto
{
    #[Assert\NotBlank]
    #[Assert\Type(type: Types::STRING)]
    public string $name;

    #[Assert\NotBlank]
    #[Assert\Type(type: Types::FLOAT)]
    public float $amount;
}
