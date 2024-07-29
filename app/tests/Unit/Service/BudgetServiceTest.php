<?php

declare(strict_types=1);

namespace App\Tests\Unit\Service;

use App\Dto\Budget\Payload\BudgetPayload;
use App\Entity\Budget;
use App\Repository\BudgetRepository;
use App\Service\BudgetService;
use App\Service\ExpenseService;
use App\Service\IncomeService;
use App\Tests\Common\Factory\BudgetFactory;
use My\RestBundle\Dto\PaginationQueryParams;
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

    private BudgetRepository $budgetRepository;

    private IncomeService $incomeService;

    private ExpenseService $expenseService;

    private Security $security;

    private BudgetService $budgetService;

    protected function setUp(): void
    {
        parent::setUp();

        $this->budgetRepository = $this->getMockBuilder(BudgetRepository::class)->disableOriginalConstructor()->getMock();
        $this->incomeService = $this->getMockBuilder(IncomeService::class)->disableOriginalConstructor()->getMock();
        $this->expenseService = $this->getMockBuilder(ExpenseService::class)->disableOriginalConstructor()->getMock();
        $this->security = $this->getMockBuilder(Security::class)->disableOriginalConstructor()->getMock();

        $this->budgetService = new BudgetService(
            budgetRepository: $this->budgetRepository,
            incomeService: $this->incomeService,
            expenseService: $this->expenseService,
            security: $this->security,
        );
    }

    #[TestDox('When calling create budget, it should update and return the budget created')]
    #[Test]
    public function createBudgetService_WhenDataOk_ReturnsBudgetCreated(): void
    {
        // ARRANGE
        $budget = BudgetFactory::createOne([
            'id' => 1,
            'user' => $this->security->getUser(),
        ]);

        $BudgetPayload = (new BudgetPayload());
        $BudgetPayload->date = new \DateTime('2022-03');
        $BudgetPayload->incomes = [];
        $BudgetPayload->expenses = [];

        $this->budgetRepository->expects($this->once())
            ->method('save')
            ->willReturnCallback(static function (Budget $budget): void {
                $budget->setId(1)
                    ->setDate(new \DateTime('2022-03'))
                    ->updateName()
                ;
            })
        ;

        // ACT
        $budgetResponse = $this->budgetService->create($BudgetPayload);

        // ASSERT
        $this->assertInstanceOf(Budget::class, $budget);
        $this->assertSame($budget->getId(), $budgetResponse->getId());
        $this->assertSame('Budget 2022-03', $budgetResponse->getName());
    }

    #[TestDox('When calling update budget, it should update and return the budget updated')]
    #[Test]
    public function updateBudgetService_WhenDataOk_ReturnsBudgetUpdated(): void
    {
        // ARRANGE
        $budget = BudgetFactory::createOne([
            'id' => 1,
            'user' => $this->security->getUser(),
        ]);

        $updateBudgetPayload = (new BudgetPayload());
        $updateBudgetPayload->date = new \DateTime('2022-03');
        $updateBudgetPayload->incomes = [];
        $updateBudgetPayload->expenses = [];

        $this->budgetRepository->expects($this->once())
            ->method('save')
            ->willReturnCallback(static function (Budget $budget): void {
                $budget->setId(1)
                    ->setDate(new \DateTime('2022-03'))
                    ->updateName()
                ;
            })
        ;

        // ACT
        $budgetResponse = $this->budgetService->update($updateBudgetPayload, $budget);

        // ASSERT
        $this->assertInstanceOf(Budget::class, $budget);
        $this->assertSame($budget->getId(), $budgetResponse->getId());
        $this->assertSame('Budget 2022-03', $budgetResponse->getName());
    }

    #[TestDox('When calling update budget with bad user, it should returns access denied exception')]
    #[Test]
    public function updateBudgetService_WithBadUser_ReturnsAccessDeniedException(): void
    {
        // ASSERT
        $this->expectException(AccessDeniedHttpException::class);

        // ARRANGE
        $budget = BudgetFactory::createOne([
            'id' => 1,
        ]);

        $updateBudgetPayload = (new BudgetPayload());
        $updateBudgetPayload->date = new \DateTime('2022-01');

        // ACT
        $this->budgetService->update($updateBudgetPayload, $budget);
    }

    #[TestDox('When calling get budget, it should get the budget')]
    #[Test]
    public function getBudgetService_WhenDataOk_ReturnsBudget(): void
    {
        // ARRANGE
        $budget = BudgetFactory::createOne([
            'user' => $this->security->getUser(),
            'name' => 'Budget 2022-01',
        ]);

        $this->budgetRepository->expects($this->once())
            ->method('find')
            ->willReturn($budget)
        ;

        // ACT
        $budgetResponse = $this->budgetService->get($budget->getId());

        // ASSERT
        $this->assertInstanceOf(Budget::class, $budget);
        $this->assertSame($budget->getId(), $budgetResponse->getId());
        $this->assertSame('Budget 2022-01', $budgetResponse->getName());
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
        $budget = BudgetFactory::new()->withoutPersisting()->create();

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
        $budget = BudgetFactory::createOne([
            'user' => $this->security->getUser(),
        ]);

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
        $budget = BudgetFactory::createOne();

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

    #[TestDox('When calling checkAccess, it should returns an AccessDeniedException')]
    #[Test]
    public function checkAccessBudgetService_WhenBadData_ReturnsAccessDeniedException(): void
    {
        // ASSERT
        $this->expectException(AccessDeniedHttpException::class);

        // ARRANGE PRIVATE METHOD TEST
        $budgetService = new BudgetService($this->budgetRepository, $this->incomeService, $this->expenseService, $this->security);
        $method = $this->getPrivateMethod(BudgetService::class, 'checkAccess');

        // ARRANGE
        $budget = BudgetFactory::createOne([
            'id' => 1,
        ]);

        // ACT
        $method->invoke($budgetService, $budget);
    }

    private function getPrivateMethod(string $className, string $methodName): \ReflectionMethod
    {
        return (new \ReflectionClass($className))->getMethod($methodName);
    }
}
