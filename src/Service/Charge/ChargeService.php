<?php

declare(strict_types=1);

namespace App\Service\Charge;

use App\Entity\Charge;
use Doctrine\ORM\EntityManagerInterface;

class ChargeService
{
    public function __construct(
        private readonly EntityManagerInterface $em,
    ) {
    }

    public function create(Charge $charge): Charge
    {
        $this->em->persist($charge);
        $this->em->flush();

        return $charge;
    }

    public function update(Charge $charge): Charge
    {
        $this->em->flush();

        return $charge;
    }

    public function delete(Charge $charge): void
    {
        $this->em->remove($charge);
        $this->em->flush();
    }
}
