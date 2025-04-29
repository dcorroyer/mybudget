<?php

declare(strict_types=1);

namespace App\Budget\Dto\Response;

use App\Budget\Enum\PayementMethodEnum;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'ExpenseResponse', description: 'Expense data',)]
class ExpenseResponse
{
    public function __construct(
        #[OA\Property(description: 'Unique expense identifier', type: 'integer', example: 33)]
        public readonly int $id,

        #[OA\Property(description: 'Expense name', type: 'string', example: 'Loyer')]
        public readonly string $name,

        #[OA\Property(description: 'Expense amount', type: 'number', format: 'float', example: 800)]
        public readonly float $amount,

        #[OA\Property(description: 'Expense category', type: 'string', example: 'Habitation')]
        public readonly string $category,

        #[OA\Property(description: 'Payment method', type: 'string', enum: [
            'OTHER',
            'BILLS_ACCOUNT',
            'BANK_TRANSFER',
        ], example: PayementMethodEnum::OTHER->value)]
        public readonly PayementMethodEnum $paymentMethod,
    ) {
    }
}
