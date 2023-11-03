<?php

declare(strict_types=1);

namespace App\Dto\Income\Http;

use App\Contract\ORMFilterInterface;
use App\Contract\QueryFilterInterface;
use Doctrine\Common\Collections\Criteria;
use Symfony\Component\Serializer\Annotation\SerializedPath;

class IncomeFilterQuery implements QueryFilterInterface, ORMFilterInterface
{
    #[SerializedPath('[criteria][query]')]
    public ?string $query = null;

    #[SerializedPath('[i][amount]')]
    public ?float $amount = 0;

    private int $page = 1;

    private int $limit = 20;

    public function getCriteria(): Criteria
    {
        $criteria = Criteria::create();

        if ($this->amount) {
            $criteria->andWhere(Criteria::expr()->eq('amount', $this->amount));
        }

        if ($this->query) {
            $criteria->andWhere(Criteria::expr()->contains('name', ucfirst($this->query)));
        }

        return $criteria;
    }

    public function getPage(): int
    {
        return $this->page;
    }

    public function setPage(int $page): self
    {
        $this->page = $page;

        return $this;
    }

    public function getLimit(): int
    {
        return $this->limit;
    }

    public function setLimit(int $limit): self
    {
        $this->limit = $limit;

        return $this;
    }
}
