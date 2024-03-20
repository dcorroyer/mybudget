<?php

declare(strict_types=1);

namespace App\Dto\Budget\Http;

use Doctrine\Common\Collections\Criteria;
use My\RestBundle\Contract\ORMFilterInterface;
use My\RestBundle\Contract\QueryFilterInterface;
use Symfony\Component\Serializer\Annotation\SerializedPath;

class BudgetFilterQuery implements QueryFilterInterface, ORMFilterInterface
{
    #[SerializedPath('[criteria][query]')]
    private ?string $query = null;

    #[SerializedPath('[i][name]')]
    private ?string $name = null;

    public function getCriteria(): Criteria
    {
        $criteria = Criteria::create();

        if ($this->name !== null && $this->name !== '' && $this->name !== '0') {
            $criteria->andWhere(Criteria::expr()->contains('name', $this->name));
        }

        if ($this->query !== null && $this->query !== '' && $this->query !== '0') {
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

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): self
    {
        $this->name = $name;

        return $this;
    }
}
