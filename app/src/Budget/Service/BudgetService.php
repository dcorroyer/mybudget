<?php

declare(strict_types=1);

namespace App\Budget\Service;

use App\Budget\Dto\Payload\BudgetPayload;
use App\Budget\Dto\Response\BudgetResponse;
use App\Budget\Dto\Response\ExpenseResponse;
use App\Budget\Dto\Response\IncomeResponse;
use App\Budget\Entity\Budget;
use App\Budget\Repository\BudgetRepository;
use App\Budget\Security\Voter\BudgetVoter;
use App\Shared\Dto\PaginatedResponseDto;
use App\Shared\Dto\PaginationMetaDto;
use App\Shared\Dto\PaginationQueryParams;
use App\Shared\Entity\User;
use App\Shared\Enum\ErrorMessagesEnum;
use Carbon\Carbon;
use Doctrine\Common\Collections\Criteria;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

class BudgetService
{
    public function __construct(
        private readonly BudgetRepository $budgetRepository,
        private readonly IncomeService $incomeService,
        private readonly ExpenseService $expenseService,
        private readonly Security $security,
        private readonly AuthorizationCheckerInterface $authorizationChecker,
    ) {
    }

    public function get(int $id): BudgetResponse
    {
        $budget = $this->budgetRepository->find($id);

        if ($budget === null) {
            throw new NotFoundHttpException(ErrorMessagesEnum::BUDGET_NOT_FOUND->value);
        }

        if (! $this->authorizationChecker->isGranted(BudgetVoter::VIEW, $budget)) {
            throw new AccessDeniedHttpException(ErrorMessagesEnum::ACCESS_DENIED->value);
        }

        return $this->createBudgetResponse($budget);
    }

    public function create(BudgetPayload $budgetPayload): BudgetResponse
    {
        $budget = new Budget();

        return $this->createOrUpdateBudget($budgetPayload, $budget);
    }

    public function update(BudgetPayload $budgetPayload, Budget $budget): BudgetResponse
    {
        if (! $this->authorizationChecker->isGranted(BudgetVoter::EDIT, $budget)) {
            throw new AccessDeniedHttpException(ErrorMessagesEnum::ACCESS_DENIED->value);
        }

        $budget->clearIncomes();
        $budget->clearExpenses();

        return $this->createOrUpdateBudget($budgetPayload, $budget);
    }

    private function createOrUpdateBudget(BudgetPayload $budgetPayload, Budget $budget): BudgetResponse
    {
        /** @var User $user */
        $user = $this->security->getUser();

        $budget->setDate($budgetPayload->date)
            ->setUser($user)
        ;

        $incomes = $budgetPayload->incomes ?? [];
        $expenses = $budgetPayload->expenses ?? [];

        foreach ($incomes as $incomePayload) {
            $income = $this->incomeService->create($incomePayload, $budget);
            $budget->addIncome($income);
        }

        foreach ($expenses as $expensePayload) {
            $expense = $this->expenseService->create($expensePayload, $budget);
            $budget->addExpense($expense);
        }

        $this->budgetRepository->save($budget, true);

        return $this->createBudgetResponse($budget);
    }

    public function delete(Budget $budget): void
    {
        if (! $this->authorizationChecker->isGranted(BudgetVoter::DELETE, $budget)) {
            throw new AccessDeniedHttpException(ErrorMessagesEnum::ACCESS_DENIED->value);
        }

        $this->budgetRepository->delete($budget, true);
    }

    public function duplicate(?int $id = null): BudgetResponse
    {
        if ($id === null) {
            $budget = $this->budgetRepository->findLatestByUser($this->security->getUser());
        } else {
            $budget = $this->budgetRepository->find($id);
        }

        if ($budget === null) {
            throw new NotFoundHttpException(ErrorMessagesEnum::BUDGET_NOT_FOUND->value);
        }

        if (! $this->authorizationChecker->isGranted(BudgetVoter::VIEW, $budget)) {
            throw new AccessDeniedHttpException(ErrorMessagesEnum::ACCESS_DENIED->value);
        }

        $newBudget = new Budget();

        $newBudget->setName($budget->getName());
        $newBudget->setIncomesAmount($budget->getIncomesAmount());
        $newBudget->setExpensesAmount($budget->getExpensesAmount());
        $newBudget->setSavingCapacity($budget->getSavingCapacity());
        $newBudget->setUser($budget->getUser());

        $newDate = Carbon::parse($this->budgetRepository->findLatestByUser($budget->getUser())?->getDate())
            ->startOfMonth()
            ->addMonth()
        ;
        $newBudget->setDate($newDate);

        foreach ($budget->getIncomes() as $income) {
            $newIncome = clone $income;
            $newIncome->setBudget($newBudget);
            $newBudget->addIncome($newIncome);
        }

        foreach ($budget->getExpenses() as $expense) {
            $newExpense = clone $expense;
            $newExpense->setBudget($newBudget);
            $newBudget->addExpense($newExpense);
        }

        $this->budgetRepository->save($newBudget, true);

        return $this->createBudgetResponse($newBudget);
    }

    public function paginate(
        ?int $year = null,
        ?PaginationQueryParams $paginationQueryParams = null,
    ): PaginatedResponseDto {
        $criteria = Criteria::create();

        if ($year !== null) {
            $startDate = (new \DateTimeImmutable("{$year}-01-01"))->format('Y-m-d');
            $endDate = (new \DateTimeImmutable("{$year}-12-31"))->format('Y-m-d');

            $criteria->andWhere(Criteria::expr()->gte('date', $startDate));
            $criteria->andWhere(Criteria::expr()->lte('date', $endDate));
        }

        $criteria->andWhere(Criteria::expr()->eq('user', $this->security->getUser()));
        $criteria->orderBy([
            'date' => 'DESC',
        ]);

        $paginated = $this->budgetRepository->paginate($paginationQueryParams, null, $criteria);

        $budgets = [];

        /** @var Budget $budget */
        foreach ($paginated->getItems() as $budget) {
            $budgets[] = $this->createBudgetResponse($budget);
        }

        return new PaginatedResponseDto(
            data: $budgets,
            meta: new PaginationMetaDto(
                total: $paginated->getTotalItemCount(),
                page: $paginated->getCurrentPageNumber(),
                limit: $paginated->getItemNumberPerPage(),
            ),
        );
    }

    private function createBudgetResponse(Budget $budget): BudgetResponse
    {
        $incomes = [];
        foreach ($budget->getIncomes() as $income) {
            $incomes[] = new IncomeResponse(
                id: $income->getId(),
                name: $income->getName(),
                amount: $income->getAmount()
            );
        }

        $expenses = [];
        foreach ($budget->getExpenses() as $expense) {
            $expenses[] = new ExpenseResponse(
                id: $expense->getId(),
                name: $expense->getName(),
                amount: $expense->getAmount(),
                category: $expense->getCategory(),
                paymentMethod: $expense->getPaymentMethod(),
            );
        }

        return new BudgetResponse(
            id: $budget->getId(),
            name: $budget->getName(),
            incomesAmount: $budget->getIncomesAmount(),
            expensesAmount: $budget->getExpensesAmount(),
            savingCapacity: $budget->getSavingCapacity(),
            date: $budget->getDate()->format('Y-m'),
            incomes: $incomes,
            expenses: $expenses
        );
    }
}
