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
use Zenstruck\Foundry\Test\Factories;

#[Group('unit')]
#[Group('service')]
#[Group('expense-category')]
#[Group('expense-category-service')]
class ExpenseCategoryServiceTest extends TestCase
{
    use SerializerTrait;
    use Factories;

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
    public function createExpenseCategoryService_WhenDataOk_ReturnsExpenseCategory()
    {
        // ARRANGE
        $expenseCategory = ExpenseCategoryFactory::new([
            'id' => 1,
        ])->withoutPersisting()
            ->create()
            ->object();
        $expenseCategoryPayload = new ExpenseCategoryPayload();
        $expenseCategoryPayload->setName($expenseCategory->getName());

        $this->expenseCategoryRepository
            ->expects($this->once())
            ->method('save')
            ->willReturnCallback(function (ExpenseCategory $expenseCategory) {
                $expenseCategory->setId(1);
            });

        // ACT
        $expenseCategoryResponse = $this->expenseCategoryService->create($expenseCategoryPayload);

        // ASSERT
        $this->assertInstanceOf(ExpenseCategory::class, $expenseCategoryResponse);
        $this->assertInstanceOf(ExpenseCategory::class, $expenseCategory);
        $this->assertEquals($expenseCategory->getId(), $expenseCategoryResponse->getId());
        $this->assertEquals($expenseCategory->getName(), $expenseCategoryResponse->getName());
    }

    #[TestDox('When you call paginate, it should return the expense categories list')]
    #[Test]
    public function paginateExpenseCategoryService_WhenDataOk_ReturnsExpenseCategoriesList()
    {
        // ARRANGE
        $expenseCategories = ExpenseCategoryFactory::new()->withoutPersisting()->createMany(20);
        $pagination = PaginationTestHelper::getPagination($expenseCategories);

        $this->expenseCategoryRepository->method('paginate')
            ->willReturn($pagination);

        // ACT
        $expenseCategoriesResponse = $this->expenseCategoryService->paginate(new PaginationQueryParams());

        // ASSERT
        $this->assertCount(20, $expenseCategoriesResponse);
    }
}
