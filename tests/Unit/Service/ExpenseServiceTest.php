<?php

declare(strict_types=1);

namespace App\Tests\Unit\Service;

use App\Dto\Expense\Payload\ExpensePayload;
use App\Dto\Expense\Response\ExpenseResponse;
use App\Entity\Expense;
use App\Repository\ExpenseCategoryRepository;
use App\Repository\ExpenseLineRepository;
use App\Repository\ExpenseRepository;
use App\Service\ExpenseCategoryService;
use App\Service\ExpenseService;
use App\Tests\Common\Factory\ExpenseFactory;
use My\RestBundle\Dto\PaginationQueryParams;
use My\RestBundle\Test\Common\Trait\SerializerTrait;
use My\RestBundle\Test\Helper\PaginationTestHelper;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\TestDox;
use PHPUnit\Framework\TestCase;
use Zenstruck\Foundry\Test\Factories;

#[Group('unit')]
#[Group('service')]
#[Group('expense')]
#[Group('expense-service')]
class ExpenseServiceTest extends TestCase
{
    use SerializerTrait;
    use Factories;

    private ExpenseRepository $expenseRepository;

    private ExpenseService $expenseService;

    protected function setUp(): void
    {
        parent::setUp();

        $this->expenseRepository = $this->createMock(ExpenseRepository::class);
        $expenseLineRepository = $this->createMock(ExpenseLineRepository::class);
        $expenseCategoryRepository = $this->createMock(ExpenseCategoryRepository::class);
        $expenseCategoryService = $this->createMock(ExpenseCategoryService::class);

        $this->expenseService = new ExpenseService(
            $this->expenseRepository,
            $expenseLineRepository,
            $expenseCategoryRepository,
            $expenseCategoryService
        );
    }

    #[TestDox('When calling create income, it should create and return a new expense')]
    #[Test]
    public function createExpenseService_WhenDataOk_ReturnsExpense()
    {
        // ARRANGE
        $expense = ExpenseFactory::new([
            'id' => 1,
        ])->withoutPersisting()
            ->create()
            ->object();

        $expensePayload = (new ExpensePayload())
            ->setDate($expense->getDate());

        $this->expenseRepository->expects($this->once())
            ->method('save')
            ->willReturnCallback(function (Expense $expense) {
                $expense->setId(1);
            });

        // ACT
        $expenseResponse = $this->expenseService->create($expensePayload);

        // ASSERT
        $this->assertInstanceOf(ExpenseResponse::class, $expenseResponse);
        $this->assertInstanceOf(Expense::class, $expense);
        $this->assertEquals($expense->getId(), $expenseResponse->getId());
        $this->assertEquals($expense->getDate(), $expenseResponse->getDate());
    }

    #[TestDox('When calling update income, it should update and return the expense')]
    #[Test]
    public function updateExpenseService_WhenDataOk_ReturnsExpense()
    {
        // ARRANGE
        $expense = ExpenseFactory::new([
            'id' => 1,
        ])->withoutPersisting()
            ->create()
            ->object();

        $expensePayload = (new ExpensePayload())
            ->setDate(new \DateTime('now'));

        $this->expenseRepository->expects($this->once())
            ->method('save')
            ->willReturnCallback(function (Expense $expense) {
                $expense->setId(1);
            });

        // ACT
        $expenseResponse = $this->expenseService->update($expensePayload, $expense);

        // ASSERT
        $this->assertInstanceOf(ExpenseResponse::class, $expenseResponse);
        $this->assertInstanceOf(Expense::class, $expense);
        $this->assertEquals($expense->getId(), $expenseResponse->getId());
        $this->assertEquals($expensePayload->getDate(), $expenseResponse->getDate());
    }

    #[TestDox('When calling delete income, it should delete the income')]
    #[Test]
    public function deleteExpenseService_WhenDataOk_ReturnsNoContent()
    {
        // ARRANGE
        $expense = ExpenseFactory::new()->withoutPersisting()->create()->object();

        // ACT
        $expenseResponse = $this->expenseService->delete($expense);

        // ASSERT
        $this->assertInstanceOf(Expense::class, $expense);
        $this->assertEquals($expense->getId(), $expenseResponse->getId());
    }

    #[TestDox('When you call paginate, it should return the expenses list')]
    #[Test]
    public function paginateExpenseService_WhenDataOk_ReturnsExpensesList()
    {
        // ARRANGE
        $incomes = ExpenseFactory::new()->withoutPersisting()->createMany(20);
        $pagination = PaginationTestHelper::getPagination($incomes);

        $this->expenseRepository->method('paginate')
            ->willReturn($pagination);

        // ACT
        $incomesResponse = $this->expenseService->paginate(new PaginationQueryParams());

        // ASSERT
        $this->assertCount(20, $incomesResponse);
    }
}
