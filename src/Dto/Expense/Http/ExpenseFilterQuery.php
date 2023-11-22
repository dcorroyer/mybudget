<?php

declare(strict_types=1);

namespace App\Dto\Expense\Http;

use App\Trait\Http\PaginationFilterQueryTrait;
use Doctrine\Common\Collections\Criteria;
use My\RestBundle\Contract\ORMFilterInterface;
use My\RestBundle\Contract\QueryFilterInterface;
use Symfony\Component\Serializer\Annotation\SerializedPath;

class ExpenseFilterQuery implements QueryFilterInterface, ORMFilterInterface
{
    use PaginationFilterQueryTrait;

    #[SerializedPath('[criteria][query]')]
    private ?string $query;

    #[SerializedPath('[i][amount]')]
    private ?float $amount = 0;

    public function getCriteria(): Criteria
    {
        $criteria = Criteria::create();

        if ($this->amount) {
            $criteria->andWhere(Criteria::expr()->eq('amount', $this->amount));
        }

        if ($this->query) {
            $criteria->andWhere(Criteria::expr()->contains('date', ucfirst($this->query)));
        }

        return $criteria;
    }

    public function getQuery(): ?string
    {
        return $this->query;
    }

    public function setQuery(?string $query): self
    {
        $this->query = $query;

        return $this;
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
