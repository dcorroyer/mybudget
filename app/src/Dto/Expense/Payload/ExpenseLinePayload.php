<?php

declare(strict_types=1);

namespace App\Dto\Expense\Payload;

use App\Dto\ExpenseCategory\Payload\ExpenseCategoryPayload;
use App\Trait\Payload\AmountPayloadTrait;
use App\Trait\Payload\IdPayloadTrait;
use App\Trait\Payload\NamePayloadTrait;
use My\RestBundle\Contract\PayloadInterface;
use Symfony\Component\Validator\Constraints as Assert;

class ExpenseLinePayload implements PayloadInterface
{
    use AmountPayloadTrait;
    use IdPayloadTrait;
    use NamePayloadTrait;

    #[Assert\NotBlank]
    private ExpenseCategoryPayload $category;

    public function getCategory(): ExpenseCategoryPayload
    {
        return $this->category;
    }

    public function setCategory(ExpenseCategoryPayload $expenseCategoryPayload): self
    {
        $this->category = $expenseCategoryPayload;

        return $this;
    }
}
