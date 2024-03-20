<?php

declare(strict_types=1);

namespace App\Tests\Unit\Service;

use App\Dto\Budget\Payload\BudgetPayload;
use App\Dto\Budget\Payload\UpdateBudgetPayload;
use App\Dto\Budget\Response\BudgetResponse;
use App\Entity\Budget;
use App\Repository\BudgetRepository;
use App\Repository\ExpenseRepository;
use App\Repository\IncomeRepository;
use App\Service\BudgetService;
use App\Tests\Common\Factory\BudgetFactory;
use My\RestBundle\Dto\PaginationQueryParams;
use My\RestBundle\Test\Common\Trait\SerializerTrait;
use My\RestBundle\Test\Helper\PaginationTestHelper;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\TestDox;
use PHPUnit\Framework\TestCase;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Zenstruck\Foundry\Test\Factories;

/**
 * @internal
 */
#[Group('unit')]
#[Group('service')]
#[Group('budget')]
#[Group('budget-service')]
class BudgetServiceTest extends TestCase
{
    use Factories;
    use SerializerTrait;

    private BudgetRepository $budgetRepository;

    private IncomeRepository $incomeRepository;

    private ExpenseRepository $expenseRepository;

    private BudgetService $budgetService;

    private Security $security;

    protected function setUp(): void
    {
        parent::setUp();

        $this->budgetRepository = $this->createMock(BudgetRepository::class);
        $this->incomeRepository = $this->createMock(IncomeRepository::class);
        $this->expenseRepository = $this->createMock(ExpenseRepository::class);
        $this->security = $this->createMock(Security::class);

        $this->budgetService = new BudgetService(
            budgetRepository: $this->budgetRepository,
            incomeRepository: $this->incomeRepository,
            expenseRepository: $this->expenseRepository,
            security: $this->security,
        );
    }

    #[TestDox('When calling create budget, it should create and return a new budget')]
    #[Test]
    public function createBudgetService_WhenDataOk_ReturnsBudget(): void
    {
        // ARRANGE
        $budget = BudgetFactory::new([
            'id' => 1,
        ])->withoutPersisting()
            ->create()
            ->object()
        ;

        $budgetPayload = (new BudgetPayload())
            ->setDate($budget->getDate())
            ->setIncomeId($budget->getIncome()->getId())
            ->setExpenseId($budget->getExpense()->getId())
        ;

        $this->incomeRepository->expects($this->once())
            ->method('find')
            ->willReturn($budget->getIncome())
        ;

        $this->expenseRepository->expects($this->once())
            ->method('find')
            ->willReturn($budget->getExpense())
        ;

        $this->budgetRepository->expects($this->once())
            ->method('save')
            ->willReturnCallback(static function (Budget $budget): void {
                $budget->setId(1)
                    ->updateName()
                ;
            })
        ;

        // ACT
        $budgetResponse = $this->budgetService->create($budgetPayload);

        // ASSERT
        $this->assertInstanceOf(BudgetResponse::class, $budgetResponse);
        $this->assertInstanceOf(Budget::class, $budget);
        $this->assertSame($budget->getId(), $budgetResponse->getId());
    }

    #[TestDox('When calling create budget without income or expense, it should throw an InvalidArgumentException')]
    #[Test]
    public function createBudgetService_WhenBadData_ReturnsInvalidArgumentException(): void
    {
        // ASSERT
        $this->expectException(\InvalidArgumentException::class);

        // ARRANGE
        $budget = BudgetFactory::new([
            'id' => 1,
        ])->withoutPersisting()
            ->create()
            ->object()
        ;

        $budgetPayload = (new BudgetPayload())
            ->setDate($budget->getDate())
            ->setIncomeId($budget->getIncome()->getId())
            ->setExpenseId($budget->getExpense()->getId())
        ;

        $this->incomeRepository->expects($this->once())
            ->method('find')
            ->willReturn(null)
        ;

        $this->expenseRepository->expects($this->once())
            ->method('find')
            ->willReturn(null)
        ;

        // ACT
        $this->budgetService->create($budgetPayload);
    }

    #[TestDox('When calling update budget, it should update and return the budget updated')]
    #[Test]
    public function updateBudgetService_WhenDataOk_ReturnsBudgetUpdated(): void
    {
        // ARRANGE
        $budget = BudgetFactory::new([
            'id' => 1,
            'user' => $this->security->getUser(),
        ])->withoutPersisting()
            ->create()
            ->object()
        ;

        $updateBudgetPayload = (new UpdateBudgetPayload())
            ->setDate(new \DateTime('2022-01'))
        ;

        $this->budgetRepository->expects($this->once())
            ->method('save')
            ->willReturnCallback(static function (Budget $budget): void {
                $budget->setId(1)
                    ->updateName()
                ;
            })
        ;

        // ACT
        $budgetResponse = $this->budgetService->update($updateBudgetPayload, $budget);

        // ASSERT
        $this->assertInstanceOf(BudgetResponse::class, $budgetResponse);
        $this->assertInstanceOf(Budget::class, $budget);
        $this->assertSame($budget->getId(), $budgetResponse->getId());
    }

    #[TestDox('When calling update budget with bad user, it should returns access denied exception')]
    #[Test]
    public function updateBudgetService_WithBadUser_ReturnsAccessDeniedException(): void
    {
        // ASSERT
        $this->expectException(AccessDeniedHttpException::class);

        // ARRANGE
        $budget = BudgetFactory::new([
            'id' => 1,
        ])->withoutPersisting()
            ->create()
            ->object()
        ;

        $updateBudgetPayload = (new UpdateBudgetPayload())
            ->setDate(new \DateTime('2022-01'))
        ;

        // ACT
        $this->budgetService->update($updateBudgetPayload, $budget);
    }

    #[TestDox('When calling get budget, it should get the budget')]
    #[Test]
    public function getBudgetService_WhenDataOk_ReturnsBudget(): void
    {
        // ARRANGE
        $budget = BudgetFactory::new([
            'user' => $this->security->getUser(),
        ])->withoutPersisting()
            ->create()
            ->object()
        ;

        $this->budgetRepository->expects($this->once())
            ->method('find')
            ->willReturn($budget)
        ;

        // ACT
        $budgetResponse = $this->budgetService->get($budget->getId());

        // ASSERT
        $this->assertInstanceOf(Budget::class, $budget);
        $this->assertSame($budget->getId(), $budgetResponse->getId());
    }

    #[TestDox('When calling get budget with bad id, it should throw not found exception')]
    #[Test]
    public function getBudgetService_WithBadId_ReturnsNotFoundException(): void
    {
        // ASSERT
        $this->expectException(NotFoundHttpException::class);

        // ACT
        $this->budgetService->get(999);
    }

    #[TestDox('When calling get budget for another user, it should throw access denied exception')]
    #[Test]
    public function getBudgetService_WithBadUser_ReturnsAccessDeniedException(): void
    {
        // ASSERT
        $this->expectException(AccessDeniedHttpException::class);

        // ARRANGE
        $budget = BudgetFactory::new()->withoutPersisting()->create()->object();

        $this->budgetRepository->expects($this->once())
            ->method('find')
            ->willReturn($budget)
        ;

        // ACT
        $this->budgetService->get($budget->getId());
    }

    #[TestDox('When calling delete budget, it should delete the budget')]
    #[Test]
    public function deleteBudgetService_WhenDataOk_ReturnsNoContent(): void
    {
        // ARRANGE
        $budget = BudgetFactory::new([
            'user' => $this->security->getUser(),
        ])->withoutPersisting()
            ->create()
            ->object()
        ;

        // ACT
        $budgetResponse = $this->budgetService->delete($budget);

        // ASSERT
        $this->assertInstanceOf(Budget::class, $budget);
        $this->assertSame($budget->getId(), $budgetResponse->getId());
    }

    #[TestDox('When calling delete budget with bad user, it should returns access denied exception')]
    #[Test]
    public function deleteBudgetService_WithBadUser_ReturnsAccessDeniedException(): void
    {
        // ASSERT
        $this->expectException(AccessDeniedHttpException::class);

        // ARRANGE
        $budget = BudgetFactory::new()->withoutPersisting()->create()->object();

        // ACT
        $this->budgetService->delete($budget);
    }

    #[TestDox('When you call paginate, it should return the budgets list')]
    #[Test]
    public function paginateBudgetService_WhenDataOk_ReturnsBudgetsList(): void
    {
        // ARRANGE
        $budgets = BudgetFactory::new()->withoutPersisting()->createMany(20);
        $slidingPagination = PaginationTestHelper::getPagination($budgets);

        $this->budgetRepository->method('paginate')
            ->willReturn($slidingPagination)
        ;

        // ACT
        $budgetsResponse = $this->budgetService->paginate(new PaginationQueryParams());

        // ASSERT
        $this->assertCount(20, $budgetsResponse);
    }

    #[TestDox('When calling budgetResponse, it should returns the budget response')]
    #[Test]
    public function budgetResponseBudgetService_WhenDataContainsNewName_ReturnsBudgetResponse(): void
    {
        // ARRANGE PRIVATE METHOD TEST
        $budgetService = new BudgetService($this->budgetRepository, $this->incomeRepository, $this->expenseRepository, $this->security);
        $method = $this->getPrivateMethod(BudgetService::class, 'budgetResponse');

        // ARRANGE
        $budget = BudgetFactory::new([
            'id' => 1,
        ])->withoutPersisting()
            ->create()
            ->object()
        ;

        $budget->updateName();

        // ACT
        $budgetResponse = $method->invoke($budgetService, $budget);

        // ASSERT
        $this->assertInstanceOf(BudgetResponse::class, $budgetResponse);
        $this->assertSame($budget->getId(), $budgetResponse->getId());
    }

    #[TestDox('When calling checkAccess, it should returns an AccessDeniedException')]
    #[Test]
    public function checkAccessBudgetService_WhenBadData_ReturnsAccessDeniedException(): void
    {
        // ASSERT
        $this->expectException(AccessDeniedHttpException::class);

        // ARRANGE PRIVATE METHOD TEST
        $budgetService = new BudgetService($this->budgetRepository, $this->incomeRepository, $this->expenseRepository, $this->security);
        $method = $this->getPrivateMethod(BudgetService::class, 'checkAccess');

        // ARRANGE
        $budget = BudgetFactory::new([
            'id' => 1,
        ])->withoutPersisting()
            ->create()
            ->object()
        ;

        // ACT
        $method->invoke($budgetService, $budget);
    }

    private function getPrivateMethod(string $className, string $methodName): \ReflectionMethod
    {
        $reflectionClass = new \ReflectionClass($className);

        return $reflectionClass->getMethod($methodName);
    }
}
