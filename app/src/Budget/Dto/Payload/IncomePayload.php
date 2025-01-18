<?php

declare(strict_types=1);

namespace App\Budget\Dto\Payload;

use Doctrine\DBAL\Types\Types;
use Symfony\Component\Validator\Constraints as Assert;

class IncomePayload
{
    #[Assert\NotBlank]
    #[Assert\Type(type: Types::STRING)]
    public string $name;

    #[Assert\NotBlank]
    #[Assert\Type(type: Types::FLOAT)]
    public float $amount;
}
