<?php

declare(strict_types=1);

namespace App\Dto\Transaction\Payload;

use App\Enum\TransactionTypesEnum;
use Symfony\Component\Validator\Constraints as Assert;

class TransactionPayload
{
    #[Assert\NotBlank]
    #[Assert\Type('string')]
    public string $description;

    #[Assert\NotBlank]
    #[Assert\Type('float')]
    public float $amount;

    #[Assert\NotBlank]
    #[Assert\Type('string')]
    public TransactionTypesEnum $type;

    #[Assert\NotBlank]
    #[Assert\Date]
    public \DateTimeInterface $date;

    #[Assert\NotBlank]
    #[Assert\Type('integer')]
    public int $accountId;
}
