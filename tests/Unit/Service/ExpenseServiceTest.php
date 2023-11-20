<?php

declare(strict_types=1);

namespace App\Tests\Unit\Service;

use App\Dto\Expense\Payload\ExpensePayload;
use App\Dto\Expense\Response\ExpenseResponse;
use App\Entity\Expense;
use App\Repository\CategoryRepository;
use App\Repository\ExpenseRepository;
use App\Service\CategoryService;
use App\Service\ExpenseService;
use App\Tests\Common\Factory\ExpenseFactory;
use My\RestBundle\Test\Common\Trait\SerializerTrait;
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

    private CategoryRepository $categoryRepository;

    private CategoryService $categoryService;

    protected function setUp(): void
    {
        parent::setUp();

        $this->expenseRepository = $this->createMock(ExpenseRepository::class);
        $this->categoryRepository = $this->createMock(CategoryRepository::class);
        $this->categoryService = $this->createMock(CategoryService::class);

        $this->expenseService = new ExpenseService(
            $this->expenseRepository,
            $this->categoryRepository,
            $this->categoryService
        );
    }

    #[TestDox('When calling create income, it should create and return a new income')]
    #[Test]
    public function create_WhenDataOk_ReturnsExpense()
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
}
