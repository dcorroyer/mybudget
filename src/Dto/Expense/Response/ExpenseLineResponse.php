<?php

declare(strict_types=1);

namespace App\Dto\Expense\Response;

use App\Dto\ExpenseCategory\Response\ExpenseCategoryResponse;
use App\Serializable\SerializationGroups;
use App\Trait\Response\AmountResponseTrait;
use App\Trait\Response\IdResponseTrait;
use App\Trait\Response\NameResponseTrait;
use My\RestBundle\Contract\ResponseInterface;
use Symfony\Component\Serializer\Annotation as Serializer;

class ExpenseLineResponse implements ResponseInterface
{
    use IdResponseTrait;
    use NameResponseTrait;
    use AmountResponseTrait;

    #[Serializer\Groups([SerializationGroups::EXPENSE_CREATE, SerializationGroups::EXPENSE_UPDATE])]
    private ExpenseCategoryResponse $category;

    public function getCategory(): ExpenseCategoryResponse
    {
        return $this->category;
    }

    public function setCategory(ExpenseCategoryResponse $category): self
    {
        $this->category = $category;

        return $this;
    }
}
