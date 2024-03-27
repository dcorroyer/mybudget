<?php

declare(strict_types=1);

namespace App\Tests\Unit\Service;

use App\Dto\ExpenseCategory\Payload\ExpenseCategoryPayload;
use App\Entity\ExpenseCategory;
use App\Repository\ExpenseCategoryRepository;
use App\Service\ExpenseCategoryService;
use App\Tests\Common\Factory\ExpenseCategoryFactory;
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
#[Group('expense-category')]
#[Group('expense-category-service')]
class ExpenseCategoryServiceTest extends TestCase
{
    use Factories;
    use SerializerTrait;

    private ExpenseCategoryService $expenseCategoryService;

    private ExpenseCategoryRepository $expenseCategoryRepository;

    protected function setUp(): void
    {
        parent::setUp();

        $this->expenseCategoryRepository = $this->createMock(ExpenseCategoryRepository::class);

        $this->expenseCategoryService = new ExpenseCategoryService($this->expenseCategoryRepository);
    }

    #[TestDox('When calling create expense category, it should create and return a new expense category')]
    #[Test]
    public function createExpenseCategoryService_WhenDataOk_ReturnsExpenseCategory(): void
    {
        // ARRANGE
        $expenseCategory = ExpenseCategoryFactory::new([
            'id' => 1,
        ])->withoutPersisting()
            ->create()
            ->object()
        ;

        $expenseCategoryPayload = (new ExpenseCategoryPayload())
            ->setName($expenseCategory->getName())
        ;

        $this->expenseCategoryRepository
            ->expects($this->once())
            ->method('save')
            ->willReturnCallback(static function (ExpenseCategory $expenseCategory): void {
                $expenseCategory->setId(1);
            })
        ;

        // ACT
        $expenseCategoryResponse = $this->expenseCategoryService->create($expenseCategoryPayload);

        // ASSERT
        $this->assertInstanceOf(ExpenseCategory::class, $expenseCategoryResponse);
        $this->assertInstanceOf(ExpenseCategory::class, $expenseCategory);
        $this->assertSame($expenseCategory->getId(), $expenseCategoryResponse->getId());
        $this->assertSame($expenseCategory->getName(), $expenseCategoryResponse->getName());
    }

    #[TestDox('When calling manage expense category with the Id of an existing category, it should returns the expense category')]
    #[Test]
    public function manageExpenseCategoryCategoryExpenseService_WhenDataContainsId_ReturnsExpenseCategory(): void
    {
        // ARRANGE PRIVATE METHOD TEST
        $expenseService = new ExpenseCategoryService($this->expenseCategoryRepository);
        $method = $this->getPrivateMethod(ExpenseCategoryService::class, 'manageExpenseCategory');

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
    public function manageExpenseCategoryExpenseCategoryService_WhenDataContainsName_ReturnsExpenseCategory(): void
    {
        // ARRANGE PRIVATE METHOD TEST
        $expenseService = new ExpenseCategoryService($this->expenseCategoryRepository);
        $method = $this->getPrivateMethod(ExpenseCategoryService::class, 'manageExpenseCategory');

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
    public function manageExpenseCategoryExpenseCategoryService_WhenDataContainsNewName_ReturnsExpenseCategory(): void
    {
        // ARRANGE PRIVATE METHOD TEST
        $expenseService = new ExpenseCategoryService($this->expenseCategoryRepository);
        $method = $this->getPrivateMethod(ExpenseCategoryService::class, 'manageExpenseCategory');

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
            ->willReturn(null)
        ;

        // ACT
        $expenseCategoryResponse = $method->invoke($expenseService, $expenseCategoryPayload);

        // ASSERT
        $this->assertInstanceOf(ExpenseCategory::class, $expenseCategoryResponse);
        $this->assertSame($expenseCategory->getName(), $expenseCategoryResponse->getName());
    }

    private function getPrivateMethod(string $className, string $methodName): \ReflectionMethod
    {
        $reflectionClass = new \ReflectionClass($className);

        return $reflectionClass->getMethod($methodName);
    }
}
