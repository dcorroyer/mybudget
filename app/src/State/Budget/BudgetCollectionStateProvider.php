<?php

declare(strict_types=1);

namespace App\State\Budget;

use ApiPlatform\Metadata\Operation;
use App\ApiResource\BudgetResource;
use App\Entity\User;
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
        /** @var User $user */
        $user = $this->getUser();

        $qb = $this->budgetRepository->getBudgetsByUser(user: $user);

        return $this->mapCollection(collection: $qb, target: BudgetResource::class, operation: $operation, context: $context);
    }
}
