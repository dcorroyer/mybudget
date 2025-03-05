<?php

declare(strict_types=1);

namespace App\Savings\Dto\Payload;

use App\Shared\Enum\TransactionTypesEnum;
use Doctrine\DBAL\Types\Types;
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
    #[OA\Property(description: 'Transaction description', type: 'string', example: 'Monthly salary')]
    public string $description;

    #[Assert\NotBlank]
    #[Assert\Type(type: Types::FLOAT)]
    #[Assert\GreaterThan(0)]
    #[OA\Property(description: 'Transaction amount', type: 'number', format: 'float', example: 500)]
    public float $amount;

    #[Assert\NotBlank]
    #[Assert\Type(TransactionTypesEnum::class)]
    #[OA\Property(description: 'Transaction type', type: 'string', enum: [
        'DEBIT',
        'CREDIT',
    ], example: TransactionTypesEnum::DEBIT->value)]
    public TransactionTypesEnum $type;

    #[Assert\NotBlank]
    #[OA\Property(description: 'Transaction date', type: 'string', format: 'date', example: '2023-05-15')]
    public \DateTimeInterface $date;
}
