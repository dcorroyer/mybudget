<?php

declare(strict_types=1);

namespace App\Tests\Unit\Service;

use App\Budget\Dto\Payload\ExpensePayload;
use App\Budget\Entity\Expense;
use App\Budget\Repository\ExpenseRepository;
use App\Budget\Service\ExpenseService;
use App\Tests\Common\Factory\BudgetFactory;
use App\Tests\Common\Factory\ExpenseFactory;
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
final class ExpenseServiceTest extends TestCase
{
    use Factories;

    private ExpenseRepository $expenseRepository;

    private ExpenseService $expenseService;

    #[\Override]
    protected function setUp(): void
    {
        parent::setUp();

        $this->expenseRepository = $this->createMock(ExpenseRepository::class);

        $this->expenseService = new ExpenseService($this->expenseRepository);
    }

    #[TestDox('When calling create expense, it should create and return a new expense')]
    #[Test]
    public function createExpenseService_WhenDataOk_ReturnsExpense(): void
    {
        // ARRANGE
        $expense = ExpenseFactory::createOne([
            'id' => 1,
        ]);

        $budget = BudgetFactory::createOne();

        $expensePayload = (new ExpensePayload());
        $expensePayload->name = $expense->getName();
        $expensePayload->amount = $expense->getAmount();
        $expensePayload->category = $expense->getCategory();
        $expensePayload->paymentMethod = $expense->getPaymentMethod();

        $this->expenseRepository->expects($this->once())
            ->method('save')
            ->willReturnCallback(static function (Expense $expense): void {
                $expense->setId(1);
            })
        ;

        // ACT
        $expenseResponse = $this->expenseService->create($expensePayload, $budget);

        // ASSERT
        self::assertInstanceOf(Expense::class, $expense);
        self::assertSame($expense->getId(), $expenseResponse->getId());
        self::assertSame($expense->getName(), $expenseResponse->getName());
        self::assertSame($expense->getAmount(), $expenseResponse->getAmount());
        self::assertSame($expense->getCategory(), $expenseResponse->getCategory());
        self::assertSame($expense->getPaymentMethod(), $expenseResponse->getPaymentMethod());
    }
}
