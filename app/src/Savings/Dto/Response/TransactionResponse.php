<?php

declare(strict_types=1);

namespace App\Savings\Dto\Response;

use App\Shared\Enum\TransactionTypesEnum;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'TransactionResponse', description: 'Transaction data',)]
class TransactionResponse
{
    public function __construct(
        #[OA\Property(description: 'Transaction identifier', example: 1, type: 'integer')]
        public readonly int $id,

        #[OA\Property(description: 'Transaction description', example: 'Monthly salary', type: 'string')]
        public readonly string $description,

        #[OA\Property(description: 'Transaction amount', example: 500, type: 'number', format: 'float')]
        public readonly float $amount,

        #[OA\Property(description: 'Transaction type', example: TransactionTypesEnum::DEBIT->value, type: 'string', enum: [
            'DEBIT',
            'CREDIT',
        ])]
        public readonly TransactionTypesEnum $type,

        #[OA\Property(description: 'Transaction date', example: '2023-05-15', type: 'string', format: 'date')]
        public readonly \DateTimeInterface $date,

        #[OA\Property(description: 'Associated account', ref: new Model(type: AccountPartialResponse::class))]
        public readonly AccountPartialResponse $account,
    ) {
    }
}
