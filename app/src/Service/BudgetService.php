<?php

declare(strict_types=1);

namespace App\Service;

use App\Dto\Budget\Http\BudgetFilterQuery;
use App\Dto\Budget\Payload\BudgetPayload;
use App\Dto\Budget\Payload\UpdateBudgetPayload;
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

        /** @var User $user */
        $user = $this->security->getUser();

        $budget->setDate($budgetPayload->getDate())
            ->setUser($user);

        foreach ($budgetPayload->getIncomes() as $incomePayload) {
            $income = $this->incomeService->create($incomePayload, $budget);
            $budget->addIncome($income);
        }

        foreach ($budgetPayload->getExpenses() as $expensePayload) {
            $expenses = $this->expenseService->create($expensePayload, $budget);

            foreach ($expenses as $expense) {
                $budget->addExpense($expense);
            }
        }

        $this->budgetRepository->save($budget, true);

        return $budget;
    }

    public function update(UpdateBudgetPayload $updateBudgetPayload, Budget $budget): Budget
    {
        $this->checkAccess($budget);

        $budget->setDate($updateBudgetPayload->getDate());

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
     * @throws \Exception
     */
    public function paginate(?PaginationQueryParams $paginationQueryParams = null, ?BudgetFilterQuery $budgetFilterQuery = null): SlidingPagination
    {
        $criteria = Criteria::create();
        $criteria->andWhere(Criteria::expr()->eq('user', $this->security->getUser()));

        return $this->budgetRepository->paginate($paginationQueryParams, $budgetFilterQuery, $criteria);
    }

    private function checkAccess(Budget $budget): void
    {
        if ($this->security->getUser() !== $budget->getUser()) {
            throw new AccessDeniedHttpException('Access denied');
        }
    }
}
