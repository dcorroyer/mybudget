<?php

declare(strict_types=1);

namespace App\Service;

use App\Dto\Expense\Payload\CategoryPayload;
use App\Entity\Category;
use App\Repository\CategoryRepository;

class CategoryService
{
    public function __construct(
        private readonly CategoryRepository $categoryRepository,
    ) {
    }

    public function create(CategoryPayload $payload, bool $flush): Category
    {
        $category = new Category();
        $category->setName($payload->getName());

        $this->categoryRepository->save($category, $flush);

        return $category;
    }
}
