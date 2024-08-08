<?php

declare(strict_types=1);

namespace App\Service;

use App\Dto\Budget\Http\BudgetFilterQuery;
use App\Dto\Budget\Payload\BudgetPayload;
use App\Entity\Budget;
use App\Entity\User;
use App\Repository\BudgetRepository;
use Doctrine\Common\Collections\Criteria;
use Knp\Bundle\PaginatorBundle\Pagination\SlidingPagination;
use My\RestBundle\Dto\PaginationQueryParams;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class BudgetService
{
    public function __construct(
        private readonly BudgetRepository $budgetRepository,
        private readonly IncomeService $incomeService,
        private readonly ExpenseService $expenseService,
        private readonly Security $security,
    ) {
    }

    public function get(int $id): Budget
    {
        $budget = $this->budgetRepository->find($id);

        if ($budget === null) {
            throw new NotFoundHttpException('Budget not found');
        }

        $this->checkAccess($budget);

        return $budget;
    }

    public function create(BudgetPayload $budgetPayload): Budget
    {
        $budget = new Budget();

        return $this->createOrUpdateBudget($budgetPayload, $budget);
    }

    public function update(BudgetPayload $budgetPayload, Budget $budget): Budget
    {
        $this->checkAccess($budget);

        // Clear existing incomes and expenses
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

    public function delete(Budget $budget): Budget
    {
        $this->checkAccess($budget);

        $this->budgetRepository->delete($budget, true);

        return $budget;
    }

    /**
     * @return SlidingPagination<int, Budget>
     */
    public function paginate(?PaginationQueryParams $paginationQueryParams = null, ?BudgetFilterQuery $budgetFilterQuery = null): SlidingPagination
    {
        $criteria = Criteria::create();
        $criteria->andWhere(Criteria::expr()->eq('user', $this->security->getUser()))
            ->orderBy([
                'date' => 'DESC',
            ])
        ;

        return $this->budgetRepository->paginate($paginationQueryParams, $budgetFilterQuery, $criteria);
    }

    private function checkAccess(Budget $budget): void
    {
        if ($this->security->getUser() !== $budget->getUser()) {
            throw new AccessDeniedHttpException('Access denied');
        }
    }
}
