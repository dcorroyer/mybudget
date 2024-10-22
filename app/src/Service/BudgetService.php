<?php

declare(strict_types=1);

namespace App\Service;

use App\Dto\Budget\Http\BudgetFilterQuery;
use App\Dto\Budget\Payload\BudgetPayload;
use App\Entity\Budget;
use App\Entity\User;
use App\Enum\ErrorMessagesEnum;
use App\Repository\BudgetRepository;
use Doctrine\Common\Collections\Criteria;
use Knp\Bundle\PaginatorBundle\Pagination\SlidingPagination;
use My\RestBundle\Dto\PaginationQueryParams;
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

    public function get(int $id): Budget
    {
        $budget = $this->budgetRepository->find($id);

        if ($budget === null) {
            throw new NotFoundHttpException(ErrorMessagesEnum::BUDGET_NOT_FOUND->value);
        }

        if (! $this->authorizationChecker->isGranted('view', $budget)) {
            throw new AccessDeniedHttpException(ErrorMessagesEnum::ACCESS_DENIED->value);
        }

        return $budget;
    }

    public function create(BudgetPayload $budgetPayload): Budget
    {
        $budget = new Budget();

        return $this->createOrUpdateBudget($budgetPayload, $budget);
    }

    public function update(BudgetPayload $budgetPayload, Budget $budget): Budget
    {
        if (! $this->authorizationChecker->isGranted('edit', $budget)) {
            throw new AccessDeniedHttpException(ErrorMessagesEnum::ACCESS_DENIED->value);
        }

        $budget->clearIncomes();
        $budget->clearExpenses();

        return $this->createOrUpdateBudget($budgetPayload, $budget);
    }

    private function createOrUpdateBudget(BudgetPayload $budgetPayload, Budget $budget): Budget
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

        return $budget;
    }

    public function delete(Budget $budget): void
    {
        if (! $this->authorizationChecker->isGranted('delete', $budget)) {
            throw new AccessDeniedHttpException(ErrorMessagesEnum::ACCESS_DENIED->value);
        }

        $this->budgetRepository->delete($budget, true);
    }

    public function duplicate(?int $id = null): Budget
    {
        if ($id === null) {
            $budget = $this->budgetRepository->findLatestByUser($this->security->getUser());
        } else {
            $budget = $this->budgetRepository->find($id);
        }

        if ($budget === null) {
            throw new NotFoundHttpException(ErrorMessagesEnum::BUDGET_NOT_FOUND->value);
        }

        if (! $this->authorizationChecker->isGranted('view', $budget)) {
            throw new AccessDeniedHttpException(ErrorMessagesEnum::ACCESS_DENIED->value);
        }

        $newBudget = new Budget();

        $newBudget->setName($budget->getName());
        $newBudget->setIncomesAmount($budget->getIncomesAmount());
        $newBudget->setExpensesAmount($budget->getExpensesAmount());
        $newBudget->setSavingCapacity($budget->getSavingCapacity());
        $newBudget->setUser($budget->getUser());

        $newDate = $this->budgetRepository->findLatestByUser($budget->getUser())->getDate(); // @phpstan-ignore-line
        $newDate->modify('+1 month'); // @phpstan-ignore-line
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

        return $newBudget;
    }

    /**
     * @return SlidingPagination<int, Budget>
     */
    public function paginate(
        ?PaginationQueryParams $paginationQueryParams = null,
        ?BudgetFilterQuery $budgetFilterQuery = null
    ): SlidingPagination {
        $criteria = Criteria::create();
        $criteria->andWhere(Criteria::expr()->eq('user', $this->security->getUser()))
            ->orderBy([
                'date' => 'DESC',
            ])
        ;

        return $this->budgetRepository->paginate($paginationQueryParams, $budgetFilterQuery, $criteria);
    }
}
