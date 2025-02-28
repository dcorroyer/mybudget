<?php

declare(strict_types=1);

namespace App\Budget\Dto\Http;

use App\Shared\Contract\ORMFilterInterface;
use Doctrine\Common\Collections\Criteria;

class BudgetFilterQuery implements ORMFilterInterface
{
    public ?int $date = null;

    #[\Override]
    public function getCriteria(): Criteria
    {
        $criteria = Criteria::create();

        if ($this->date !== null) {
            $startDate = (new \DateTimeImmutable("{$this->date}-01-01"))->format('Y-m-d');
            $endDate = (new \DateTimeImmutable("{$this->date}-12-31"))->format('Y-m-d');

            $criteria->andWhere(Criteria::expr()->gte('date', $startDate));
            $criteria->andWhere(Criteria::expr()->lte('date', $endDate));
        }

        return $criteria;
    }
}
