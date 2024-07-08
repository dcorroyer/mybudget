<?php

declare(strict_types=1);

namespace App\State\Budget;

use ApiPlatform\Metadata\Operation;
use App\ApiInput\Budget\CreateBudgetInputDto;
use App\ApiResource\BudgetResource;
use App\Entity\Budget;
use Doctrine\ORM\EntityManagerInterface;
use Rekalogika\ApiLite\State\AbstractProcessor;

/**
 * @extends AbstractProcessor<CreateBudgetInputDto, BudgetResource>
 */
class CreateBudgetProcessor extends AbstractProcessor
{
    public function __construct(
        private readonly EntityManagerInterface $em,
    ) {
    }

    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = []): BudgetResource
    {
        $budget = $this->map($data, Budget::class);

        $this->em->persist($budget);
        $this->em->flush();

        return $this->map($budget, BudgetResource::class);
    }
}
