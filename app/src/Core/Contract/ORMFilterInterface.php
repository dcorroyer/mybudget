<?php

declare(strict_types=1);

namespace App\Core\Contract;

use Doctrine\Common\Collections\Criteria;

interface ORMFilterInterface
{
    public function getCriteria(): Criteria;
}
