<?php

declare(strict_types=1);

namespace App\Savings\Dto\Payload;

use App\Shared\Enum\TransactionTypesEnum;
use OpenApi\Attributes as OA;
use Symfony\Component\Validator\Constraints as Assert;

#[OA\Schema(
    schema: 'TransactionPayload',
    description: 'Data for creating or updating a transaction',
    required: ['description', 'amount', 'type', 'date']
)]
class TransactionPayload
{
    #[Assert\NotBlank]
    #[Assert\Type('string')]
    #[OA\Property(description: 'Transaction description', example: 'Monthly salary', type: 'string')]
    public string $description;

    #[Assert\NotBlank]
    #[Assert\Type('float')]
    #[Assert\GreaterThan(0)]
    #[OA\Property(description: 'Transaction amount', example: 500, type: 'number', format: 'float')]
    public float $amount;

    #[Assert\NotBlank]
    #[Assert\Type(TransactionTypesEnum::class)]
    #[OA\Property(description: 'Transaction type', example: TransactionTypesEnum::DEBIT->value, type: 'string', enum: ['DEBIT', 'CREDIT'])]
    public TransactionTypesEnum $type;

    #[Assert\NotBlank]
    #[OA\Property(description: 'Transaction date', example: '2023-05-15', type: 'string', format: 'date')]
    public \DateTimeInterface $date;
}
