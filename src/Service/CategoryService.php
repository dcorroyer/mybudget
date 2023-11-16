<?php

declare(strict_types=1);

namespace App\Service;

use App\Dto\Expense\Payload\CategoryPayload;
use App\Entity\Category;
use App\Repository\CategoryRepository;
use My\RestBundle\Helper\DtoToEntityHelper;

class CategoryService
{
    public function __construct(
        private readonly CategoryRepository $categoryRepository,
        private readonly DtoToEntityHelper $dtoToEntityHelper,
    ) {
    }

    public function create(CategoryPayload $payload): Category
    {
        $category = new Category();

        /** @var Category $category */
        $category = $this->dtoToEntityHelper->create($payload, $category);

        $this->categoryRepository->save($category, true);

        return $category;
    }
}
