<?php

declare(strict_types=1);

namespace App\State\Budget;

use ApiPlatform\Metadata\Operation;
use App\ApiResource\BudgetResource;
use App\Repository\BudgetRepository;
use Rekalogika\ApiLite\State\AbstractProvider;

/**
 * @extends AbstractProvider<BudgetResource>
 */
class BudgetCollectionStateProvider extends AbstractProvider
{
    public function __construct(
        private readonly BudgetRepository $budgetRepository
    ) {
    }

    /**
     * @return iterable<BudgetResource>
     */
    public function provide(Operation $operation, array $uriVariables = [], array $context = []): iterable
    {
        return $this->mapCollection(collection: $this->budgetRepository, target: BudgetResource::class, operation: $operation, context: $context);
    }
}
