<?php

declare(strict_types=1);

namespace App\Budget\Dto\Payload;

use App\Shared\Api\Dto\Contract\PayloadInterface;
use Doctrine\DBAL\Types\Types;
use Symfony\Component\Validator\Constraints as Assert;

class IncomePayload implements PayloadInterface
{
    #[Assert\NotBlank]
    #[Assert\Type(type: Types::STRING)]
    public string $name;

    #[Assert\NotBlank]
    #[Assert\Type(type: Types::FLOAT)]
    public float $amount;
}
