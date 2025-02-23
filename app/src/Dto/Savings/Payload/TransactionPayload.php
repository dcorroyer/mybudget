<?php

declare(strict_types=1);

namespace App\Dto\Savings\Payload;

use App\Enum\TransactionTypesEnum;
use Symfony\Component\Validator\Constraints as Assert;

class TransactionPayload
{
    #[Assert\NotBlank]
    #[Assert\Type('string')]
    public string $description;

    #[Assert\NotBlank]
    #[Assert\Type('float')]
    #[Assert\GreaterThan(0)]
    public float $amount;

    #[Assert\NotBlank]
    #[Assert\Type(TransactionTypesEnum::class)]
    public TransactionTypesEnum $type;

    #[Assert\NotBlank]
    public \DateTimeInterface $date;
}
