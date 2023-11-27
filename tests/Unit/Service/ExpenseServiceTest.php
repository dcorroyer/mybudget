<?php

declare(strict_types=1);

namespace App\Tests\Unit\Service;

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

#[Group('unit')]
#[Group('service')]
#[Group('expense')]
#[Group('expense-service')]
class ExpenseServiceTest extends TestCase
{
    use SerializerTrait;
    use Factories;

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
    public function createExpenseService_WhenDataOk_ReturnsExpense()
    {
        // ARRANGE
        $expense = ExpenseFactory::new([
            'id' => 1,
        ])->withoutPersisting()
            ->create()
            ->object();

        $expensePayload = (new ExpensePayload());

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
    }

    #[TestDox('When calling update expense, it should update and return the expense')]
    #[Test]
    public function updateExpenseService_WhenDataOk_ReturnsExpense()
    {
        // ARRANGE
        $expense = ExpenseFactory::new([
            'id' => 1,
        ])->withoutPersisting()
            ->create()
            ->object();

        $expensePayload = (new ExpensePayload());

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
    }

    #[TestDox('When calling delete expense, it should delete the expense')]
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

    #[TestDox(
        'When calling manage expense category with the Id of an existing category, it should returns the expense category'
    )]
    #[Test]
    public function manageExpenseCategoryExpenseService_WhenDataContainsId_ReturnsExpenseCategory()
    {
        // ARRANGE PRIVATE METHOD TEST
        $object = new ExpenseService(
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
            ->object();

        $expenseCategoryPayload = (new ExpenseCategoryPayload())
            ->setId($expenseCategory->getId())
            ->setName($expenseCategory->getName());

        $this->expenseCategoryRepository->expects($this->once())
            ->method('find')
            ->willReturn($expenseCategory);

        // ACT
        $expenseCategoryResponse = $method->invoke($object, $expenseCategoryPayload);

        // ASSERT
        $this->assertInstanceOf(ExpenseCategory::class, $expenseCategoryResponse);
        $this->assertEquals($expenseCategory->getId(), $expenseCategoryResponse->getId());
        $this->assertEquals($expenseCategory->getName(), $expenseCategoryResponse->getName());
    }

    #[TestDox(
        'When calling manage expense category with the Name of an existing category, it should returns the expense category'
    )]
    #[Test]
    public function manageExpenseCategoryExpenseService_WhenDataContainsName_ReturnsExpenseCategory()
    {
        // ARRANGE PRIVATE METHOD TEST
        $object = new ExpenseService(
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
            ->object();

        $expenseCategoryPayload = (new ExpenseCategoryPayload())
            ->setName($expenseCategory->getName());

        $this->expenseCategoryRepository->expects($this->once())
            ->method('findOneBy')
            ->willReturn($expenseCategory);

        // ACT
        $expenseCategoryResponse = $method->invoke($object, $expenseCategoryPayload);

        // ASSERT
        $this->assertInstanceOf(ExpenseCategory::class, $expenseCategoryResponse);
        $this->assertEquals($expenseCategory->getId(), $expenseCategoryResponse->getId());
        $this->assertEquals($expenseCategory->getName(), $expenseCategoryResponse->getName());
    }

    #[TestDox(
        'When calling manage expense category with the Name of an existing category, it should returns the expense category'
    )]
    #[Test]
    public function manageExpenseCategoryExpenseService_WhenDataContainsNewName_ReturnsExpenseCategory()
    {
        // ARRANGE PRIVATE METHOD TEST
        $object = new ExpenseService(
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
            ->object();

        $expenseCategoryPayload = (new ExpenseCategoryPayload())
            ->setName('test2');

        $this->expenseCategoryRepository->expects($this->once())
            ->method('findOneBy')
            ->willReturn(null);

        $this->expenseCategoryService->expects($this->once())
            ->method('create')
            ->willReturn($expenseCategory);

        // ACT
        $expenseCategoryResponse = $method->invoke($object, $expenseCategoryPayload);

        // ASSERT
        $this->assertInstanceOf(ExpenseCategory::class, $expenseCategoryResponse);
        $this->assertEquals($expenseCategory->getId(), $expenseCategoryResponse->getId());
        $this->assertEquals($expenseCategory->getName(), $expenseCategoryResponse->getName());
    }

    private function getPrivateMethod($className, $methodName): \ReflectionMethod
    {
        $reflector = new \ReflectionClass($className);

        return $reflector->getMethod($methodName);
    }
}
