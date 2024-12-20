<?php

declare(strict_types=1);

namespace App\Shared\Api\Doctrine\Filter\Adapter;

use App\Shared\Api\Doctrine\Filter\FilterDefinitionBag;

interface FilterQueryDefinitionInterface
{
    public function definition(): FilterDefinitionBag;
}
