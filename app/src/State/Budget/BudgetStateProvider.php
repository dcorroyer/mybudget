<?php

declare(strict_types=1);

namespace App\State\Budget;

use ApiPlatform\Metadata\Operation;
use App\ApiResource\BudgetResource;
use App\Repository\BudgetRepository;
use Rekalogika\ApiLite\Exception\NotFoundException;
use Rekalogika\ApiLite\State\AbstractProvider;

/**
 * @extends AbstractProvider<BudgetResource>
 */
class BudgetStateProvider extends AbstractProvider
{
    public function __construct(
        private readonly BudgetRepository $budgetRepository
    ) {
    }

    public function provide(Operation $operation, array $uriVariables = [], array $context = []): BudgetResource
    {
        $user = $this->budgetRepository->find($uriVariables['id']) ?? throw new NotFoundException('Budget not found');

        return $this->map($user, BudgetResource::class);
    }
}
