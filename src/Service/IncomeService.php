<?php

declare(strict_types=1);

namespace App\Service;

use App\Dto\Income\Payload\IncomePayload;
use App\Dto\Income\Response\IncomeResponse;
use App\Entity\Income;
use App\Helper\DtoToEntityHelper;
use App\Repository\IncomeRepository;

class IncomeService
{
    public function __construct(
        private readonly IncomeRepository $incomeRepository,
        private readonly DtoToEntityHelper $dtoToEntityHelper,
    ) {
    }

    public function create(IncomePayload $payload): IncomeResponse
    {
        $income = new Income();

        /** @var Income $income */
        $income = $this->dtoToEntityHelper->create($payload, $income);

        $this->incomeRepository->save($income, true);

        return (new IncomeResponse())
            ->setId($income->getId())
            ->setName($income->getName())
            ->setAmount($income->getAmount())
            ->setDate($income->getDate())
            ->setType($income->getType())
        ;
    }

    public function update(IncomePayload $payload, Income $income): IncomeResponse
    {
        /** @var Income $income */
        $income = $this->dtoToEntityHelper->update($payload, $income);

        $this->incomeRepository->save($income, true);

        return (new IncomeResponse())
            ->setId($income->getId())
            ->setName($income->getName())
            ->setAmount($income->getAmount())
            ->setDate($income->getDate())
            ->setType($income->getType())
        ;
    }

    public function delete(Income $income): Income
    {
        $this->incomeRepository->delete($income);

        return $income;
    }
}
