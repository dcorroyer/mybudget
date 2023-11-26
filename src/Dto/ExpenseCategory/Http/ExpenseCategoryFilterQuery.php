<?php

declare(strict_types=1);

namespace App\Dto\ExpenseCategory\Http;

use Doctrine\Common\Collections\Criteria;
use My\RestBundle\Contract\ORMFilterInterface;
use My\RestBundle\Contract\QueryFilterInterface;
use Symfony\Component\Serializer\Annotation\SerializedPath;

class ExpenseCategoryFilterQuery implements QueryFilterInterface, ORMFilterInterface
{
    #[SerializedPath('[criteria][query]')]
    private ?string $query;

    public function getCriteria(): Criteria
    {
        $criteria = Criteria::create();

        if ($this->query) {
            $criteria->andWhere(Criteria::expr()->contains('name', ucfirst($this->query)));
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
}
