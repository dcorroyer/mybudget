<?php

declare(strict_types=1);

namespace App\State\Budget;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\Symfony\Security\Exception\AccessDeniedException;
use App\ApiInput\Budget\BudgetInputDto;
use App\ApiResource\BudgetResource;
use App\Entity\Budget;
use App\Repository\BudgetRepository;
use Doctrine\ORM\EntityManagerInterface;
use Rekalogika\ApiLite\Exception\NotFoundException;
use Rekalogika\ApiLite\State\AbstractProcessor;

/**
 * @extends AbstractProcessor<BudgetInputDto, BudgetResource>
 */
class UpdateBudgetProcessor extends AbstractProcessor
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly BudgetRepository $budgetRepository,
    ) {
    }

    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = []): BudgetResource
    {
        $budget = $this->budgetRepository->find($uriVariables['id']) ?? throw new NotFoundException('Budget not found');

        if (!$budget->isOwnedByUser($this->getUser())) {
            throw new AccessDeniedException('Denied access to this budget');
        }

        $this->entityManager->remove($budget);

        $updatedBudget = $this->map($data, Budget::class);
        $updatedBudget->setId($budget->getId());

        $this->entityManager->persist($updatedBudget);
        $this->entityManager->flush();

        return $this->map($budget, BudgetResource::class);
    }
}
