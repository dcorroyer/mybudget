<?php

declare(strict_types=1);

namespace App\ApiResource;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Post;
use App\ApiInput\Budget\CreateBudgetInputDto;
use App\State\Budget\BudgetCollectionStateProvider;
use App\State\Budget\BudgetStateProvider;
use App\State\Budget\CreateBudgetProcessor;
use Rekalogika\Mapper\CollectionInterface;
use Symfony\Component\Uid\Uuid;

#[ApiResource(
    uriTemplate: '/budgets',
    shortName: 'Budget',
    operations: [
        new GetCollection(uriTemplate: '/budgets', provider: BudgetCollectionStateProvider::class),
        new Get(uriTemplate: '/budgets/{id}', provider: BudgetStateProvider::class),
        new Post(uriTemplate: '/budgets', input: CreateBudgetInputDto::class, processor: CreateBudgetProcessor::class),
//        new Delete(uriTemplate: '/budgets/{id}', input: null, read: false, processor: DeleteUserProcessor::class),
//        new Patch(uriTemplate: '/budgets/{id}', input: UpdateUserInputDto::class, read: false, processor: UpdateUserProcessor::class),
    ],
)]
class BudgetResource
{
    public ?Uuid $id = null;

    public ?string $name = null;

    public ?float $savingCapacity = 0;

    public ?\DateTimeInterface $date = null;

    /**
     * @var ?CollectionInterface<int, IncomeResource>
     */
    public ?CollectionInterface $incomes = null;

    /**
     * @var ?CollectionInterface<int, ExpenseResource>
     */
    public ?CollectionInterface $expenses = null;

    public ?UserResource $user = null;
}
