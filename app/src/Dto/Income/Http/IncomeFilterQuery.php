<?php

declare(strict_types=1);

namespace App\Dto\Income\Http;

use Doctrine\Common\Collections\Criteria;
use My\RestBundle\Contract\ORMFilterInterface;
use My\RestBundle\Contract\QueryFilterInterface;
use Symfony\Component\Serializer\Annotation\SerializedPath;

class IncomeFilterQuery implements QueryFilterInterface, ORMFilterInterface
{
    #[SerializedPath('[i][amount]')]
    private ?float $amount = 0;

    public function getCriteria(): Criteria
    {
        $criteria = Criteria::create();

        if ($this->amount) {
            $criteria->andWhere(Criteria::expr()->eq('amount', $this->amount));
        }

        return $criteria;
    }

    public function getAmount(): ?float
    {
        return $this->amount;
    }

    public function setAmount(?float $amount): self
    {
        $this->amount = $amount;

        return $this;
    }
}
