<?php

declare(strict_types=1);

namespace App\Savings\Dto\Response;

use App\Savings\Enum\TransactionTypesEnum;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'TransactionResponse', description: 'Transaction data',)]
class TransactionResponse
{
    public function __construct(
        #[OA\Property(description: 'Transaction identifier', type: 'integer', example: 1)]
        public readonly int $id,

        #[OA\Property(description: 'Transaction description', type: 'string', example: 'Monthly salary')]
        public readonly string $description,

        #[OA\Property(description: 'Transaction amount', type: 'number', format: 'float', example: 500)]
        public readonly float $amount,

        #[OA\Property(
            description: 'Transaction type',
            type: 'string',
            enum: ['DEBIT', 'CREDIT'],
            example: TransactionTypesEnum::DEBIT->value)]
        public readonly TransactionTypesEnum $type,

        #[OA\Property(description: 'Transaction date', type: 'string', format: 'date', example: '2023-05-15')]
        public readonly \DateTimeInterface $date,

        #[OA\Property(ref: new Model(type: AccountPartialResponse::class), description: 'Associated account')]
        public readonly AccountPartialResponse $account,
    ) {
    }
}
