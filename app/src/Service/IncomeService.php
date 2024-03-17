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

    public function create(IncomePayload $incomePayload): IncomeResponse
    {
        $income = new Income();

        return $this->updateOrCreateIncome($incomePayload, $income);
    }

    public function update(IncomePayload $incomePayload, Income $income): IncomeResponse
    {
        return $this->updateOrCreateIncome($incomePayload, $income);
    }

    public function delete(Income $income): Income
    {
        $this->incomeRepository->delete($income);

        return $income;
    }

    public function paginate(?PaginationQueryParams $paginationQueryParams = null, ?IncomeFilterQuery $incomeFilterQuery = null): SlidingPagination
    {
        return $this->incomeRepository->paginate($paginationQueryParams, $incomeFilterQuery, Criteria::create());
    }

    private function updateOrCreateIncome(IncomePayload $incomePayload, Income $income): IncomeResponse
    {
        $incomeLinesResponse = [];

        foreach ($incomePayload->getIncomeLines() as $incomeLinePayload) {
            $incomeLine = $incomeLinePayload->getId() !== null
                ? $this->incomeLineRepository->find($incomeLinePayload->getId())
                : new IncomeLine();

            $incomeLine->setName($incomeLinePayload->getName())
                ->setAmount($incomeLinePayload->getAmount())
                ->setType($incomeLinePayload->getType())
            ;

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
