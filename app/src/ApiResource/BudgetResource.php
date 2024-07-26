<?php

declare(strict_types=1);

namespace App\ApiResource;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Put;
use App\ApiInput\Budget\BudgetInputDto;
use App\State\Budget\BudgetCollectionStateProvider;
use App\State\Budget\BudgetStateProvider;
use App\State\Budget\CreateBudgetProcessor;
use App\State\Budget\DeleteBudgetProcessor;
use App\State\Budget\UpdateBudgetProcessor;
use Rekalogika\Mapper\CollectionInterface;
use Symfony\Component\Uid\Uuid;

#[ApiResource(
    uriTemplate: '/budgets',
    shortName: 'Budget',
    operations: [
        new GetCollection(uriTemplate: '/budgets', provider: BudgetCollectionStateProvider::class),
        new Get(uriTemplate: '/budgets/{id}', provider: BudgetStateProvider::class),
        new Post(uriTemplate: '/budgets', input: BudgetInputDto::class, processor: CreateBudgetProcessor::class),
        new Delete(uriTemplate: '/budgets/{id}', input: null, read: false, processor: DeleteBudgetProcessor::class),
        new Put(uriTemplate: '/budgets/{id}', input: BudgetInputDto::class, read: false, processor: UpdateBudgetProcessor::class),
    ],
)]
class BudgetResource
{
    public ?Uuid $id = null;

    public ?string $name = null;

    public ?float $savingCapacity = 0;

    public ?\DateTimeInterface $date = null;

    /**
     * @var CollectionInterface<int, IncomeResource>|null
     */
    public ?CollectionInterface $incomes = null;

    /**
     * @var CollectionInterface<int, ExpenseResource>|null
     */
    public ?CollectionInterface $expenses = null;

    public ?UserResource $user = null;
}
