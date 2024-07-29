<?php

declare(strict_types=1);

namespace App\Tests\Unit\Service;

use App\Dto\Budget\Payload\Dependencies\ExpensePayload;
use App\Entity\Expense;
use App\Repository\ExpenseRepository;
use App\Service\ExpenseService;
use App\Tests\Common\Factory\BudgetFactory;
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

    private ExpenseService $expenseService;

    protected function setUp(): void
    {
        parent::setUp();

        $this->expenseRepository = $this->getMockBuilder(ExpenseRepository::class)->disableOriginalConstructor()->getMock();

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

        $this->expenseRepository->expects($this->once())
            ->method('save')
            ->willReturnCallback(static function (Expense $expense): void {
                $expense->setId(1);
            })
        ;

        // ACT
        $expenseResponse = $this->expenseService->create($expensePayload, $budget);

        // ASSERT
        $this->assertInstanceOf(Expense::class, $expense);
        $this->assertSame($expense->getId(), $expenseResponse->getId());
        $this->assertSame($expense->getName(), $expenseResponse->getName());
        $this->assertSame($expense->getAmount(), $expenseResponse->getAmount());
    }
}
