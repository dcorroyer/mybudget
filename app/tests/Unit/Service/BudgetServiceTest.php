<?php

declare(strict_types=1);

namespace App\Tests\Unit\Service;

use App\Dto\Budget\Payload\BudgetPayload;
use App\Entity\Budget;
use App\Enum\ErrorMessagesEnum;
use App\Repository\BudgetRepository;
use App\Service\BudgetService;
use App\Service\ExpenseService;
use App\Service\IncomeService;
use App\Tests\Common\Factory\BudgetFactory;
use Carbon\Carbon;
use My\RestBundle\Dto\PaginationQueryParams;
use My\RestBundle\Test\Helper\PaginationTestHelper;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\TestDox;
use PHPUnit\Framework\TestCase;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Zenstruck\Foundry\Test\Factories;

/**
 * @internal
 */
#[Group('unit')]
#[Group('service')]
#[Group('budget')]
#[Group('budget-service')]
final class BudgetServiceTest extends TestCase
{
    use Factories;

    private BudgetRepository $budgetRepository;

    private IncomeService $incomeService;

    private ExpenseService $expenseService;

    private Security $security;

    private BudgetService $budgetService;

    private AuthorizationCheckerInterface $authorizationChecker;

    #[\Override]
    protected function setUp(): void
    {
        parent::setUp();

        $this->budgetRepository = $this->createMock(BudgetRepository::class);
        $this->incomeService = $this->createMock(IncomeService::class);
        $this->expenseService = $this->createMock(ExpenseService::class);
        $this->security = $this->createMock(Security::class);
        $this->authorizationChecker = $this->createMock(AuthorizationCheckerInterface::class);

        $this->budgetService = new BudgetService(
            budgetRepository: $this->budgetRepository,
            incomeService: $this->incomeService,
            expenseService: $this->expenseService,
            security: $this->security,
            authorizationChecker: $this->authorizationChecker
        );
    }

    #[TestDox('When calling create budget, it should return the budget created')]
    #[Test]
    public function createBudgetService_WhenDataOk_ReturnsBudgetCreated(): void
    {
        // ARRANGE
        $budget = BudgetFactory::createOne([
            'id' => 1,
            'user' => $this->security->getUser(),
        ]);

        $budgetPayload = (new BudgetPayload());
        $budgetPayload->date = Carbon::parse('2022-03');
        $budgetPayload->incomes = [];
        $budgetPayload->expenses = [];

        $this->budgetRepository->expects($this->once())
            ->method('save')
            ->willReturnCallback(static function (Budget $budget): void {
                $budget->setId(1)
                    ->setDate(Carbon::parse('2022-03'))
                    ->updateName()
                ;
            })
        ;

        // ACT
        $budgetResponse = $this->budgetService->create($budgetPayload);

        // ASSERT
        self::assertInstanceOf(Budget::class, $budget);
        self::assertSame($budget->getId(), $budgetResponse->getId());
        self::assertSame('Budget 2022-03', $budgetResponse->getName());
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
        $updateBudgetPayload->date = Carbon::parse('2022-03');
        $updateBudgetPayload->incomes = [];
        $updateBudgetPayload->expenses = [];

        $this->authorizationChecker->expects($this->once())
            ->method('isGranted')
            ->with('edit', $budget)
            ->willReturn(true)
        ;

        $this->budgetRepository->expects($this->once())
            ->method('save')
            ->willReturnCallback(static function (Budget $budget): void {
                $budget->setId(1)
                    ->setDate(Carbon::parse('2022-03'))
                    ->updateName()
                ;
            })
        ;

        // ACT
        $budgetResponse = $this->budgetService->update($updateBudgetPayload, $budget);

        // ASSERT
        self::assertInstanceOf(Budget::class, $budget);
        self::assertSame($budget->getId(), $budgetResponse->getId());
        self::assertSame('Budget 2022-03', $budgetResponse->getName());
    }

    #[TestDox('When calling update budget with bad user, it should returns access denied exception')]
    #[Test]
    public function updateBudgetService_WithBadUser_ReturnsAccessDeniedException(): void
    {
        // ASSERT
        $this->expectException(AccessDeniedHttpException::class);
        $this->expectExceptionMessage(ErrorMessagesEnum::ACCESS_DENIED->value);
        // ARRANGE
        $budget = BudgetFactory::createOne([
            'id' => 1,
        ]);

        $updateBudgetPayload = (new BudgetPayload());
        $updateBudgetPayload->date = Carbon::parse('2022-01');

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

        $this->authorizationChecker->expects($this->once())
            ->method('isGranted')
            ->with('view', $budget)
            ->willReturn(true)
        ;

        // ACT
        $budgetResponse = $this->budgetService->get($budget->getId());

        // ASSERT
        self::assertInstanceOf(Budget::class, $budget);
        self::assertSame($budget->getId(), $budgetResponse->getId());
        self::assertSame('Budget 2022-01', $budgetResponse->getName());
    }

    #[TestDox('When calling get budget with bad id, it should throw not found exception')]
    #[Test]
    public function getBudgetService_WithBadId_ReturnsNotFoundException(): void
    {
        // ASSERT
        $this->expectException(NotFoundHttpException::class);
        $this->expectExceptionMessage(ErrorMessagesEnum::BUDGET_NOT_FOUND->value);

        // ACT
        $this->budgetService->get(999);
    }

    #[TestDox('When calling get budget for another user, it should throw access denied exception')]
    #[Test]
    public function getBudgetService_WithBadUser_ReturnsAccessDeniedException(): void
    {
        // ASSERT
        $this->expectException(AccessDeniedHttpException::class);
        $this->expectExceptionMessage(ErrorMessagesEnum::ACCESS_DENIED->value);

        // ARRANGE
        $budget = BudgetFactory::new()->withoutPersisting()->create();

        $this->budgetRepository->expects($this->once())
            ->method('find')
            ->willReturn($budget)
        ;

        $this->authorizationChecker->expects($this->once())
            ->method('isGranted')
            ->with('view', $budget)
            ->willReturn(false)
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

        $this->authorizationChecker->expects($this->once())
            ->method('isGranted')
            ->with('delete', $budget)
            ->willReturn(true)
        ;

        $this->budgetRepository->expects($this->once())
            ->method('delete')
            ->with($budget, true)
        ;

        // ACT
        $this->budgetService->delete($budget);

        // ASSERT
        self::assertInstanceOf(Budget::class, $budget);
    }

    #[TestDox('When calling delete budget with bad user, it should returns access denied exception')]
    #[Test]
    public function deleteBudgetService_WithBadUser_ReturnsAccessDeniedException(): void
    {
        // ASSERT
        $this->expectException(AccessDeniedHttpException::class);
        $this->expectExceptionMessage(ErrorMessagesEnum::ACCESS_DENIED->value);

        // ARRANGE
        $budget = BudgetFactory::createOne();

        // ACT
        $this->budgetService->delete($budget);
    }

    #[TestDox('When calling duplicate budget, it should clone and return the new budget created')]
    #[Test]
    public function duplicateBudgetService_WhenDataOk_ReturnsNewBudgetCreated(): void
    {
        // ARRANGE
        $budget = BudgetFactory::createOne([
            'user' => $this->security->getUser(),
            'date' => new \DateTime('2023-01-01'),
        ]);

        $this->budgetRepository->expects($this->once())
            ->method('find')
            ->willReturn($budget)
        ;

        $this->authorizationChecker->expects($this->once())
            ->method('isGranted')
            ->with('view', $budget)
            ->willReturn(true)
        ;

        $this->budgetRepository->expects($this->once())
            ->method('findLatestByUser')
            ->willReturn($budget)
        ;

        $this->budgetRepository->expects($this->once())
            ->method('save')
            ->willReturnCallback(static function (Budget $budget): void {
                $budget->setId(2)
                    ->setDate(new \DateTime('2023-02-01'))
                    ->updateName()
                ;
            })
        ;

        // ACT
        $budgetResponse = $this->budgetService->duplicate($budget->getId());

        // ASSERT
        self::assertInstanceOf(Budget::class, $budget);
        self::assertSame(2, $budgetResponse->getId());
        self::assertSame('Budget 2023-02', $budgetResponse->getName());
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
        self::assertCount(20, $budgetsResponse);
    }
}
