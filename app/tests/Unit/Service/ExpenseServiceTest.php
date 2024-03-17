<?php

declare(strict_types=1);

namespace App\Tests\Unit\Service;

use App\Dto\Expense\Payload\ExpenseLinePayload;
use App\Dto\Expense\Payload\ExpensePayload;
use App\Dto\Expense\Response\ExpenseResponse;
use App\Dto\ExpenseCategory\Payload\ExpenseCategoryPayload;
use App\Entity\Expense;
use App\Entity\ExpenseCategory;
use App\Repository\ExpenseCategoryRepository;
use App\Repository\ExpenseLineRepository;
use App\Repository\ExpenseRepository;
use App\Service\ExpenseCategoryService;
use App\Service\ExpenseService;
use App\Tests\Common\Factory\ExpenseCategoryFactory;
use App\Tests\Common\Factory\ExpenseFactory;
use My\RestBundle\Dto\PaginationQueryParams;
use My\RestBundle\Test\Common\Trait\SerializerTrait;
use My\RestBundle\Test\Helper\PaginationTestHelper;
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

    private ExpenseLineRepository $expenseLineRepository;

    private ExpenseCategoryRepository $expenseCategoryRepository;

    private ExpenseCategoryService $expenseCategoryService;

    private ExpenseService $expenseService;

    protected function setUp(): void
    {
        parent::setUp();

        $this->expenseRepository = $this->createMock(ExpenseRepository::class);
        $this->expenseLineRepository = $this->createMock(ExpenseLineRepository::class);
        $this->expenseCategoryRepository = $this->createMock(ExpenseCategoryRepository::class);
        $this->expenseCategoryService = $this->createMock(ExpenseCategoryService::class);

        $this->expenseService = new ExpenseService(
            $this->expenseRepository,
            $this->expenseLineRepository,
            $this->expenseCategoryRepository,
            $this->expenseCategoryService
        );
    }

    #[TestDox('When calling create expense, it should create and return a new expense')]
    #[Test]
    public function createExpenseService_WhenDataOk_ReturnsExpense(): void
    {
        // ARRANGE
        $expense = ExpenseFactory::new([
            'id' => 1,
        ])->withoutPersisting()
            ->create()
            ->object()
        ;

        $expensePayload = (new ExpensePayload());

        $this->expenseRepository->expects($this->once())
            ->method('save')
            ->willReturnCallback(static function (Expense $expense): void {
                $expense->setId(1);
            })
        ;

        // ACT
        $expenseResponse = $this->expenseService->create($expensePayload);

        // ASSERT
        $this->assertInstanceOf(ExpenseResponse::class, $expenseResponse);
        $this->assertInstanceOf(Expense::class, $expense);
        $this->assertSame($expense->getId(), $expenseResponse->getId());
    }

    #[TestDox('When calling update expense, it should update and return the expense')]
    #[Test]
    public function updateExpenseService_WhenDataOk_ReturnsExpense(): void
    {
        // ARRANGE
        $expense = ExpenseFactory::new([
            'id' => 1,
        ])->withoutPersisting()
            ->create()
            ->object()
        ;

        $expensePayload = (new ExpensePayload());

        $this->expenseRepository->expects($this->once())
            ->method('save')
            ->willReturnCallback(static function (Expense $expense): void {
                $expense->setId(1);
            })
        ;

        // ACT
        $expenseResponse = $this->expenseService->update($expensePayload, $expense);

        // ASSERT
        $this->assertInstanceOf(ExpenseResponse::class, $expenseResponse);
        $this->assertInstanceOf(Expense::class, $expense);
        $this->assertSame($expense->getId(), $expenseResponse->getId());
    }

    #[TestDox('When calling delete expense, it should delete the expense')]
    #[Test]
    public function deleteExpenseService_WhenDataOk_ReturnsNoContent(): void
    {
        // ARRANGE
        $expense = ExpenseFactory::new()->withoutPersisting()->create()->object();

        // ACT
        $expenseResponse = $this->expenseService->delete($expense);

        // ASSERT
        $this->assertInstanceOf(Expense::class, $expense);
        $this->assertSame($expense->getId(), $expenseResponse->getId());
    }

    #[TestDox('When you call paginate, it should return the expenses list')]
    #[Test]
    public function paginateExpenseService_WhenDataOk_ReturnsExpensesList(): void
    {
        // ARRANGE
        $incomes = ExpenseFactory::new()->withoutPersisting()->createMany(20);
        $slidingPagination = PaginationTestHelper::getPagination($incomes);

        $this->expenseRepository->method('paginate')
            ->willReturn($slidingPagination)
        ;

        // ACT
        $incomesResponse = $this->expenseService->paginate(new PaginationQueryParams());

        // ASSERT
        $this->assertCount(20, $incomesResponse);
    }

    #[TestDox('When calling updateOrCreateExpense, it should returns the expense response')]
    #[Test]
    public function updateOrCreateExpenseExpenseService_WhenDataOk_ReturnsExpenseResponse(): void
    {
        // ARRANGE PRIVATE METHOD TEST
        $expenseService = new ExpenseService(
            $this->expenseRepository,
            $this->expenseLineRepository,
            $this->expenseCategoryRepository,
            $this->expenseCategoryService
        );
        $method = $this->getPrivateMethod(ExpenseService::class, 'updateOrCreateExpense');

        // ARRANGE
        $expense = ExpenseFactory::new([
            'id' => 1,
        ])->withoutPersisting()
            ->create()
            ->object()
        ;

        $expenseLinePayload = (new ExpenseLinePayload())
            ->setId($expense->getExpenseLines()[0]->getId())
            ->setAmount(100)
            ->setName('test')
            ->setCategory((new ExpenseCategoryPayload())->setName('test'))
        ;

        $expenseLinePayload2 = (new ExpenseLinePayload())
            ->setId($expense->getExpenseLines()[1]->getId())
            ->setAmount(200)
            ->setName('test2')
            ->setCategory((new ExpenseCategoryPayload())->setName('test2'))
        ;

        $expenseLinesPayload = [$expenseLinePayload, $expenseLinePayload2];

        $expensePayload = (new ExpensePayload())
            ->setExpenseLines($expenseLinesPayload)
        ;

        $this->expenseLineRepository->expects($this->exactly(2))
            ->method('find')
            ->willReturn($expense->getExpenseLines()[0])
        ;

        $this->expenseRepository->expects($this->once())
            ->method('save')
            ->willReturnCallback(static function (Expense $expense): void {
                $expense->setId(1);
            })
        ;

        // ACT
        $expenseResponse = $method->invoke($expenseService, $expensePayload, $expense);

        // ASSERT
        $this->assertInstanceOf(ExpenseResponse::class, $expenseResponse);
        $this->assertSame($expense->getId(), $expenseResponse->getId());
    }

    #[TestDox('When calling manage expense category with the Id of an existing category, it should returns the expense category')]
    #[Test]
    public function manageExpenseCategoryExpenseService_WhenDataContainsId_ReturnsExpenseCategory(): void
    {
        // ARRANGE PRIVATE METHOD TEST
        $expenseService = new ExpenseService(
            $this->expenseRepository,
            $this->expenseLineRepository,
            $this->expenseCategoryRepository,
            $this->expenseCategoryService
        );
        $method = $this->getPrivateMethod(ExpenseService::class, 'manageExpenseCategory');

        // ARRANGE
        $expenseCategory = ExpenseCategoryFactory::new([
            'id' => 1,
        ])->withoutPersisting()
            ->create()
            ->object()
        ;

        $expenseCategoryPayload = (new ExpenseCategoryPayload())
            ->setId($expenseCategory->getId())
            ->setName($expenseCategory->getName())
        ;

        $this->expenseCategoryRepository->expects($this->once())
            ->method('find')
            ->willReturn($expenseCategory)
        ;

        // ACT
        $expenseCategoryResponse = $method->invoke($expenseService, $expenseCategoryPayload);

        // ASSERT
        $this->assertInstanceOf(ExpenseCategory::class, $expenseCategoryResponse);
        $this->assertSame($expenseCategory->getId(), $expenseCategoryResponse->getId());
        $this->assertSame($expenseCategory->getName(), $expenseCategoryResponse->getName());
    }

    #[TestDox('When calling manage expense category with the Name of an existing category, it should returns the expense category')]
    #[Test]
    public function manageExpenseCategoryExpenseService_WhenDataContainsName_ReturnsExpenseCategory(): void
    {
        // ARRANGE PRIVATE METHOD TEST
        $expenseService = new ExpenseService(
            $this->expenseRepository,
            $this->expenseLineRepository,
            $this->expenseCategoryRepository,
            $this->expenseCategoryService
        );
        $method = $this->getPrivateMethod(ExpenseService::class, 'manageExpenseCategory');

        // ARRANGE
        $expenseCategory = ExpenseCategoryFactory::new([
            'id' => 1,
            'name' => 'test',
        ])->withoutPersisting()
            ->create()
            ->object()
        ;

        $expenseCategoryPayload = (new ExpenseCategoryPayload())
            ->setName($expenseCategory->getName())
        ;

        $this->expenseCategoryRepository->expects($this->once())
            ->method('findOneBy')
            ->willReturn($expenseCategory)
        ;

        // ACT
        $expenseCategoryResponse = $method->invoke($expenseService, $expenseCategoryPayload);

        // ASSERT
        $this->assertInstanceOf(ExpenseCategory::class, $expenseCategoryResponse);
        $this->assertSame($expenseCategory->getId(), $expenseCategoryResponse->getId());
        $this->assertSame($expenseCategory->getName(), $expenseCategoryResponse->getName());
    }

    #[TestDox('When calling manage expense category with the Name of an existing category, it should returns the expense category')]
    #[Test]
    public function manageExpenseCategoryExpenseService_WhenDataContainsNewName_ReturnsExpenseCategory(): void
    {
        // ARRANGE PRIVATE METHOD TEST
        $expenseService = new ExpenseService(
            $this->expenseRepository,
            $this->expenseLineRepository,
            $this->expenseCategoryRepository,
            $this->expenseCategoryService
        );
        $method = $this->getPrivateMethod(ExpenseService::class, 'manageExpenseCategory');

        // ARRANGE
        $expenseCategory = ExpenseCategoryFactory::new([
            'id' => 1,
            'name' => 'test',
        ])->withoutPersisting()
            ->create()
            ->object()
        ;

        $expenseCategoryPayload = (new ExpenseCategoryPayload())
            ->setName('test2')
        ;

        $this->expenseCategoryRepository->expects($this->once())
            ->method('findOneBy')
            ->willReturn(null)
        ;

        $this->expenseCategoryService->expects($this->once())
            ->method('create')
            ->willReturn($expenseCategory)
        ;

        // ACT
        $expenseCategoryResponse = $method->invoke($expenseService, $expenseCategoryPayload);

        // ASSERT
        $this->assertInstanceOf(ExpenseCategory::class, $expenseCategoryResponse);
        $this->assertSame($expenseCategory->getId(), $expenseCategoryResponse->getId());
        $this->assertSame($expenseCategory->getName(), $expenseCategoryResponse->getName());
    }

    private function getPrivateMethod(string $className, string $methodName): \ReflectionMethod
    {
        $reflectionClass = new \ReflectionClass($className);

        return $reflectionClass->getMethod($methodName);
    }
}
