<?php

declare(strict_types=1);

namespace App\Tests\Unit\Service;

use App\Dto\ExpenseCategory\Payload\ExpenseCategoryPayload;
use App\Entity\ExpenseCategory;
use App\Repository\ExpenseCategoryRepository;
use App\Service\ExpenseCategoryService;
use App\Tests\Common\Factory\ExpenseCategoryFactory;
use My\RestBundle\Dto\PaginationQueryParams;
use My\RestBundle\Test\Common\Trait\SerializerTrait;
use My\RestBundle\Test\Helper\PaginationTestHelper;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\TestDox;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Zenstruck\Foundry\Test\Factories;

/**
 * @internal
 */
#[Group('unit')]
#[Group('service')]
#[Group('expense-category')]
#[Group('expense-category-service')]
final class ExpenseCategoryServiceTest extends TestCase
{
    use Factories;
    use SerializerTrait;

    private ExpenseCategoryRepository $expenseCategoryRepository;

    private ExpenseCategoryService $expenseCategoryService;

    #[\Override]
    protected function setUp(): void
    {
        parent::setUp();

        $this->expenseCategoryRepository = $this->createMock(ExpenseCategoryRepository::class);

        $this->expenseCategoryService = new ExpenseCategoryService(
            expenseCategoryRepository: $this->expenseCategoryRepository
        );
    }

    #[TestDox('When calling create expense category, it should create and return a new expense category')]
    #[Test]
    public function createExpenseCategoryService_WhenDataOk_ReturnsExpenseCategory(): void
    {
        // ARRANGE
        $expenseCategory = ExpenseCategoryFactory::createOne([
            'id' => 1,
        ]);

        $expenseCategoryPayload = (new ExpenseCategoryPayload());
        $expenseCategoryPayload->name = $expenseCategory->getName();

        $this->expenseCategoryRepository->expects($this->once())
            ->method('save')
            ->willReturnCallback(static function (ExpenseCategory $expenseCategory): void {
                $expenseCategory->setId(1);
            })
        ;

        // ACT
        $expenseCategoryResponse = $this->expenseCategoryService->create($expenseCategoryPayload);

        // ASSERT
        self::assertInstanceOf(ExpenseCategory::class, $expenseCategory);
        self::assertSame($expenseCategory->getId(), $expenseCategoryResponse->getId());
        self::assertSame($expenseCategory->getName(), $expenseCategoryResponse->getName());
    }

    #[TestDox('When calling get expense category, it should get and return an expense category')]
    #[Test]
    public function getExpenseCategoryService_WhenDataOk_ReturnsExpenseCategory(): void
    {
        // ARRANGE
        $expenseCategory = ExpenseCategoryFactory::createOne([
            'id' => 1,
        ]);

        $this->expenseCategoryRepository->expects($this->once())
            ->method('find')
            ->willReturn($expenseCategory)
        ;

        // ACT
        $expenseCategoryResponse = $this->expenseCategoryService->get($expenseCategory->getId());

        // ASSERT
        self::assertInstanceOf(ExpenseCategory::class, $expenseCategory);
        self::assertSame($expenseCategory->getId(), $expenseCategoryResponse->getId());
        self::assertSame($expenseCategory->getName(), $expenseCategoryResponse->getName());
    }

    #[TestDox('When calling get expense category, it should get and return an expense category')]
    #[Test]
    public function getExpenseCategoryService_WhenNoData_ReturnsNotFoundException(): void
    {
        // ASSERT
        $this->expectException(NotFoundHttpException::class);

        // ARRANGE
        $expenseCategory = ExpenseCategoryFactory::createOne([
            'id' => 1,
        ]);

        $this->expenseCategoryRepository->expects($this->once())
            ->method('find')
            ->willReturn(null)
        ;

        // ACT
        $this->expenseCategoryService->get($expenseCategory->getId());
    }

    #[TestDox('When calling delete expense category, it should delete and return an expense category')]
    #[Test]
    public function deleteExpenseCategoryService_WhenDataOk_ReturnsDeletedExpenseCategory(): void
    {
        // ARRANGE
        $expenseCategory = ExpenseCategoryFactory::createOne([
            'id' => 1,
        ]);

        // ACT
        $expenseCategoryResponse = $this->expenseCategoryService->delete($expenseCategory);

        // ASSERT
        self::assertInstanceOf(ExpenseCategory::class, $expenseCategory);
        self::assertSame($expenseCategory->getId(), $expenseCategoryResponse->getId());
        self::assertSame($expenseCategory->getName(), $expenseCategoryResponse->getName());
    }

    #[TestDox('When you call paginate, it should return the expense categories list')]
    #[Test]
    public function paginateExpenseCategoryService_WhenDataOk_ReturnsExpenseCategoryList(): void
    {
        // ARRANGE
        $expenseCategories = ExpenseCategoryFactory::new()->withoutPersisting()->createMany(20);
        $slidingPagination = PaginationTestHelper::getPagination($expenseCategories);

        $this->expenseCategoryRepository->method('paginate')
            ->willReturn($slidingPagination)
        ;

        // ACT
        $expenseCategoriesResponse = $this->expenseCategoryService->paginate(new PaginationQueryParams());

        // ASSERT
        self::assertCount(20, $expenseCategoriesResponse);
    }
}
