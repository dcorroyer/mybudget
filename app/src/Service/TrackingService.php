<?php

declare(strict_types=1);

namespace App\Service;

use App\Dto\Tracking\Http\TrackingFilterQuery;
use App\Dto\Tracking\Payload\TrackingPayload;
use App\Dto\Tracking\Payload\UpdateTrackingPayload;
use App\Dto\Tracking\Response\TrackingResponse;
use App\Entity\Tracking;
use App\Entity\User;
use App\Repository\ExpenseRepository;
use App\Repository\IncomeRepository;
use App\Repository\TrackingRepository;
use Doctrine\Common\Collections\Criteria;
use Knp\Bundle\PaginatorBundle\Pagination\SlidingPagination;
use My\RestBundle\Dto\PaginationQueryParams;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class TrackingService
{
    public function __construct(
        private readonly TrackingRepository $trackingRepository,
        private readonly IncomeRepository $incomeRepository,
        private readonly ExpenseRepository $expenseRepository,
        private readonly Security $security,
    ) {
    }

    public function get(int $id): Tracking
    {
        $tracking = $this->trackingRepository->find($id);

        if ($tracking === null) {
            throw new NotFoundHttpException('Tracking not found');
        }

        $this->checkAccess($tracking);

        return $tracking;
    }

    public function create(TrackingPayload $trackingPayload): TrackingResponse
    {
        $tracking = new Tracking();

        $income = $this->incomeRepository->find($trackingPayload->getIncomeId());
        $expense = $this->expenseRepository->find($trackingPayload->getExpenseId());

        /** @var User $user */
        $user = $this->security->getUser();

        if ($income === null || $expense === null) {
            throw new \InvalidArgumentException('Income or Expense not found');
        }

        $tracking->setDate($trackingPayload->getDate())
            ->setIncome($income)
            ->setExpense($expense)
            ->setUser($user)
        ;

        $this->trackingRepository->save($tracking, true);

        return $this->trackingResponse($tracking);
    }

    public function update(UpdateTrackingPayload $updateTrackingPayload, Tracking $tracking): TrackingResponse
    {
        $this->checkAccess($tracking);

        $tracking->setDate($updateTrackingPayload->getDate());

        $this->trackingRepository->save($tracking, true);

        return $this->trackingResponse($tracking);
    }

    public function delete(Tracking $tracking): Tracking
    {
        $this->checkAccess($tracking);

        $this->trackingRepository->delete($tracking);

        return $tracking;
    }

    public function paginate(?PaginationQueryParams $paginationQueryParams = null, ?TrackingFilterQuery $trackingFilterQuery = null): SlidingPagination
    {
        $criteria = Criteria::create();
        $criteria->andWhere(Criteria::expr()->eq('user', $this->security->getUser()));

        return $this->trackingRepository->paginate($paginationQueryParams, $trackingFilterQuery, $criteria);
    }

    private function trackingResponse(Tracking $tracking): TrackingResponse
    {
        return (new TrackingResponse())
            ->setId($tracking->getId())
            ->setName($tracking->getName())
            ->setDate($tracking->getDate())
            ->setSavingCapacity($tracking->getSavingCapacity())
            ->setIncome($tracking->getIncome())
            ->setExpense($tracking->getExpense())
        ;
    }

    private function checkAccess(Tracking $tracking): void
    {
        if ($this->security->getUser() !== $tracking->getUser()) {
            throw new AccessDeniedHttpException('Access denied');
        }
    }
}
