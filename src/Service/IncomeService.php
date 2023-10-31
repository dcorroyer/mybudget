<?php

declare(strict_types=1);

namespace App\Service;

use App\Dto\Income\Payload\IncomePayload;
use App\Dto\Income\Response\IncomeResponse;
use App\Entity\Income;
use App\Helper\PayloadToEntityHelper;
use Doctrine\ORM\EntityManagerInterface;

class IncomeService
{
    public function __construct(
        private readonly EntityManagerInterface $em,
        private readonly PayloadToEntityHelper $payloadHelper,
    ) {
    }

    public function create(IncomePayload $payload): IncomeResponse
    {
        $income = new Income();

        /** @var Income $income */
        $income = $this->payloadHelper->create($payload, $income);

        $this->em->persist($income);
        $this->em->flush();

        return (new IncomeResponse())
            ->setId($income->getId())
            ->setName($income->getName())
            ->setAmount($income->getAmount())
            ->setDate($income->getDate())
            ->setType($income->getType())
        ;
    }
}
