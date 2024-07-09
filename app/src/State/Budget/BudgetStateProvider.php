<?php

declare(strict_types=1);

namespace App\State\Budget;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\Symfony\Security\Exception\AccessDeniedException;
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
        $budget = $this->budgetRepository->find($uriVariables['id']) ?? throw new NotFoundException('Budget not found');

        if (!$budget->isOwnedByUser($this->getUser())) {
            throw new AccessDeniedException('Denied access to this budget');
        }

        return $this->map($budget, BudgetResource::class);
    }
}
