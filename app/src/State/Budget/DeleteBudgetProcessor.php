<?php

declare(strict_types=1);

namespace App\State\Budget;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\Symfony\Security\Exception\AccessDeniedException;
use App\ApiInput\User\CreateUserInputDto;
use App\ApiResource\BudgetResource;
use App\Entity\User;
use App\Repository\BudgetRepository;
use Doctrine\ORM\EntityManagerInterface;
use Rekalogika\ApiLite\Exception\NotFoundException;
use Rekalogika\ApiLite\State\AbstractProcessor;

/**
 * @extends AbstractProcessor<CreateUserInputDto, BudgetResource>
 */
class DeleteBudgetProcessor extends AbstractProcessor
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly BudgetRepository $budgetRepository
    ) {
    }

    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = []): BudgetResource
    {
        $budget = $this->budgetRepository->find($uriVariables['id']) ?? throw new NotFoundException('Budget not found');

        /** @var User $user */
        $user = $this->getUser();

        if (! $budget->isOwnedByUser($user)) {
            throw new AccessDeniedException('Denied access to this budget');
        }

        $this->entityManager->remove($budget);
        $this->entityManager->flush();

        return $this->map($budget, BudgetResource::class);
    }
}
