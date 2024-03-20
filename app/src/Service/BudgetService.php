<?php

declare(strict_types=1);

namespace App\Service;

use App\Dto\Budget\Http\BudgetFilterQuery;
use App\Dto\Budget\Payload\BudgetPayload;
use App\Dto\Budget\Payload\UpdateBudgetPayload;
use App\Dto\Budget\Response\BudgetResponse;
use App\Entity\Budget;
use App\Entity\User;
use App\Repository\BudgetRepository;
use App\Repository\ExpenseRepository;
use App\Repository\IncomeRepository;
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
        private readonly IncomeRepository $incomeRepository,
        private readonly ExpenseRepository $expenseRepository,
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

    public function create(BudgetPayload $budgetPayload): BudgetResponse
    {
        $budget = new Budget();

        $income = $this->incomeRepository->find($budgetPayload->getIncomeId());
        $expense = $this->expenseRepository->find($budgetPayload->getExpenseId());

        /** @var User $user */
        $user = $this->security->getUser();

        if ($income === null || $expense === null) {
            throw new \InvalidArgumentException('Income or Expense not found');
        }

        $budget->setDate($budgetPayload->getDate())
            ->setIncome($income)
            ->setExpense($expense)
            ->setUser($user)
        ;

        $this->budgetRepository->save($budget, true);

        return $this->budgetResponse($budget);
    }

    public function update(UpdateBudgetPayload $updateBudgetPayload, Budget $budget): BudgetResponse
    {
        $this->checkAccess($budget);

        $budget->setDate($updateBudgetPayload->getDate());

        $this->budgetRepository->save($budget, true);

        return $this->budgetResponse($budget);
    }

    public function delete(Budget $budget): Budget
    {
        $this->checkAccess($budget);

        $this->budgetRepository->delete($budget);

        return $budget;
    }

    public function paginate(?PaginationQueryParams $paginationQueryParams = null, ?BudgetFilterQuery $budgetFilterQuery = null): SlidingPagination
    {
        $criteria = Criteria::create();
        $criteria->andWhere(Criteria::expr()->eq('user', $this->security->getUser()));

        return $this->budgetRepository->paginate($paginationQueryParams, $budgetFilterQuery, $criteria);
    }

    private function budgetResponse(Budget $budget): BudgetResponse
    {
        return (new BudgetResponse())
            ->setId($budget->getId())
            ->setName($budget->getName())
            ->setDate($budget->getDate())
            ->setSavingCapacity($budget->getSavingCapacity())
            ->setIncome($budget->getIncome())
            ->setExpense($budget->getExpense())
        ;
    }

    private function checkAccess(Budget $budget): void
    {
        if ($this->security->getUser() !== $budget->getUser()) {
            throw new AccessDeniedHttpException('Access denied');
        }
    }
}
