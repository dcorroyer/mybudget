<?php

declare(strict_types=1);

namespace App\ApiResource;

class ExpenseResource
{
    public ?string $name = null;

    public ?float $amount = 0;

    public ?string $category = null;
}
