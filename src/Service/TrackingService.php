<?php

declare(strict_types=1);

namespace App\Service;

use App\Dto\Tracking\Http\TrackingFilterQuery;
use App\Dto\Tracking\Payload\TrackingPayload;
use App\Dto\Tracking\Payload\UpdateTrackingPayload;
use App\Dto\Tracking\Response\TrackingResponse;
use App\Entity\Tracking;
use App\Repository\ExpenseRepository;
use App\Repository\IncomeRepository;
use App\Repository\TrackingRepository;
use Doctrine\Common\Collections\Criteria;
use Knp\Bundle\PaginatorBundle\Pagination\SlidingPagination;
use My\RestBundle\Dto\PaginationQueryParams;

class TrackingService
{
    public function __construct(
        private readonly TrackingRepository $trackingRepository,
        private readonly IncomeRepository $incomeRepository,
        private readonly ExpenseRepository $expenseRepository
    ) {
    }

    public function create(TrackingPayload $payload): TrackingResponse
    {
        $tracking = new Tracking();

        $income = $this->incomeRepository->find($payload->getIncomeId());
        $expense = $this->expenseRepository->find($payload->getExpenseId());

        if ($income === null || $expense === null) {
            throw new \InvalidArgumentException('Income or Expense not found');
        }

        $tracking->setDate($payload->getDate())
            ->setIncome($income)
            ->setExpense($expense);

        $this->trackingRepository->save($tracking, true);

        return $this->trackingResponse($tracking);
    }

    public function update(UpdateTrackingPayload $payload, Tracking $tracking): TrackingResponse
    {
        $tracking->setDate($payload->getDate());

        $this->trackingRepository->save($tracking, true);

        return $this->trackingResponse($tracking);
    }

    public function delete(Tracking $tracking): Tracking
    {
        $this->trackingRepository->delete($tracking);

        return $tracking;
    }

    public function paginate(
        PaginationQueryParams $paginationQueryParams = null,
        TrackingFilterQuery $filter = null
    ): SlidingPagination {
        return $this->trackingRepository->paginate($paginationQueryParams, $filter, Criteria::create());
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
}
