<?php

declare(strict_types=1);

namespace App\Service;

use App\Dto\Income\Http\IncomeFilterQuery;
use App\Dto\Income\Payload\IncomePayload;
use App\Dto\Income\Response\IncomeLineResponse;
use App\Dto\Income\Response\IncomeResponse;
use App\Entity\Income;
use App\Entity\IncomeLine;
use App\Repository\IncomeLineRepository;
use App\Repository\IncomeRepository;
use Doctrine\Common\Collections\Criteria;
use Knp\Bundle\PaginatorBundle\Pagination\SlidingPagination;
use My\RestBundle\Dto\PaginationQueryParams;

class IncomeService
{
    public function __construct(
        private readonly IncomeRepository $incomeRepository,
        private readonly IncomeLineRepository $incomeLineRepository
    ) {
    }

    public function create(IncomePayload $payload): IncomeResponse
    {
        $income = new Income();

        return $this->updateOrCreateIncome($payload, $income);
    }

    public function update(IncomePayload $payload, Income $income): IncomeResponse
    {
        return $this->updateOrCreateIncome($payload, $income);
    }

    public function delete(Income $income): Income
    {
        $this->incomeRepository->delete($income);

        return $income;
    }

    public function paginate(
        PaginationQueryParams $paginationQueryParams = null,
        IncomeFilterQuery $filter = null
    ): SlidingPagination {
        return $this->incomeRepository->paginate($paginationQueryParams, $filter, Criteria::create());
    }

    private function updateOrCreateIncome(IncomePayload $payload, Income $income): IncomeResponse
    {
        $incomeLinesResponse = [];

        foreach ($payload->getIncomeLines() as $incomeLinePayload) {
            $incomeLine = $incomeLinePayload->getId() !== null
                ? $this->incomeLineRepository->find($incomeLinePayload->getId())
                : new IncomeLine();

            $incomeLine->setAmount($incomeLinePayload->getAmount())
                ->setType($incomeLinePayload->getType());

            $income->addIncomeLine($incomeLine);
        }

        $this->incomeRepository->save($income, true);

        foreach ($income->getIncomeLines() as $incomeLine) {
            $incomeLinesResponse[] = (new IncomeLineResponse())
                ->setId($incomeLine->getId())
                ->setName($incomeLine->getName())
                ->setAmount($incomeLine->getAmount())
                ->setType($incomeLine->getType())
            ;
        }

        return (new IncomeResponse())
            ->setId($income->getId())
            ->setAmount($income->getAmount())
            ->setIncomeLines($incomeLinesResponse)
        ;
    }
}
