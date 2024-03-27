<?php

declare(strict_types=1);

namespace App\Tests\Unit\Service;

use App\Dto\Expense\Payload\ExpenseLinePayload;
use App\Dto\Expense\Payload\ExpensePayload;
use App\Dto\ExpenseCategory\Payload\ExpenseCategoryPayload;
use App\Entity\Expense;
use App\Repository\ExpenseRepository;
use App\Service\ExpenseCategoryService;
use App\Service\ExpenseService;
use App\Tests\Common\Factory\BudgetFactory;
use App\Tests\Common\Factory\ExpenseCategoryFactory;
use App\Tests\Common\Factory\ExpenseFactory;
use My\RestBundle\Test\Common\Trait\SerializerTrait;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\TestDox;
use PHPUnit\Framework\TestCase;
use Zenstruck\Foundry\Test\Factories;

/**
 * @internal
 */
#[Group('unit')]
#[Group('service')]
#[Group('expense')]
#[Group('expense-service')]
class ExpenseServiceTest extends TestCase
{
    use Factories;
    use SerializerTrait;

    private ExpenseRepository $expenseRepository;

    private ExpenseCategoryService $expenseCategoryService;

    private ExpenseService $expenseService;

    protected function setUp(): void
    {
        parent::setUp();

        $this->expenseRepository = $this->createMock(ExpenseRepository::class);
        $this->expenseCategoryService = $this->createMock(ExpenseCategoryService::class);

        $this->expenseService = new ExpenseService($this->expenseRepository, $this->expenseCategoryService);
    }

    #[TestDox('When calling create expense, it should create and return a new expense')]
    #[Test]
    public function createExpenseService_WhenDataOk_ReturnsExpense(): void
    {
        // ARRANGE
        $expenses = ExpenseFactory::new([
            'id' => 1,
        ])->withoutPersisting()
            ->createMany(5)
        ;

        /** @var ExpenseLinePayload[] $expenseLinesPayload */
        $expenseLinesPayload = $expenses;

        $budget = BudgetFactory::new()->withoutPersisting()->create()->object();

        $expenseCategory = ExpenseCategoryFactory::new()->withoutPersisting()->create()->object();

        $expenseCategoryPayload = (new ExpenseCategoryPayload())
            ->setName($expenseCategory->getName())
        ;

        $expensePayload = (new ExpensePayload())
            ->setCategory($expenseCategoryPayload)
            ->setExpenseLines($expenseLinesPayload)
        ;

        $this->expenseCategoryService->expects($this->once())
            ->method('manageExpenseCategory')
            ->willReturn($expenseCategory)
        ;

        foreach ($expenses as $ignored) {
            $this->expenseRepository
                ->method('save')
                ->willReturnCallback(static function (Expense $expense): void {
                    $expense->setId(1);
                })
            ;
        }

        // ACT
        $expenseResponse = $this->expenseService->create($expensePayload, $budget);

        // ASSERT
        $this->assertCount(5, $expenseResponse);
        $this->assertSame($expenses[0]->getAmount(), $expenseResponse[0]->getAmount());
    }
}
