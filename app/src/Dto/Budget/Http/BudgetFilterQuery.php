<?php

declare(strict_types=1);

namespace App\Dto\Budget\Http;

use Doctrine\Common\Collections\Criteria;
use My\RestBundle\Contract\ORMFilterInterface;
use My\RestBundle\Contract\QueryFilterInterface;
use OpenApi\Attributes as OA;

class BudgetFilterQuery implements QueryFilterInterface, ORMFilterInterface
{
    #[OA\Parameter(name: 'name', description: 'Filter by name')]
    public ?string $name = null;

    #[\Override]
    public function getCriteria(): Criteria
    {
        $criteria = Criteria::create();

        if ($this->name !== null) {
            $criteria->andWhere(Criteria::expr()->contains('name', ucfirst($this->name)));
        }

        return $criteria;
    }
}
