<?php

declare(strict_types=1);

namespace App\Service;

use Amp\ByteStream\Payload;
use App\Dto\Expense\Payload\ExpenseLinePayload;
use App\Entity\ExpenseLine;
use App\Repository\CategoryRepository;
use App\Repository\ExpenseLineRepository;
use Doctrine\DBAL\Exception;
use My\RestBundle\Helper\DtoToEntityHelper;

class ExpenseLineService
{
    public function __construct(
        private readonly ExpenseLineRepository $expenseLineRepository,
        private readonly CategoryService $categoryService,
        private readonly CategoryRepository $categoryRepository,
        private readonly DtoToEntityHelper $dtoToEntityHelper,
    ) {
    }

    public function create(ExpenseLinePayload $payload): ExpenseLine
    {
//        if ($payload->getCategory() !== null) {
//            if (!$this->categoryRepository->findOneBy(['name' => $payload->getCategory()->getName()])) {
//                $this->categoryService->create($payload->getCategory());
//            }
//
//            $category = $this->categoryRepository->findOneBy(['name' => $payload->getCategory()->getName()]);
//        }

        $expenseLine = new ExpenseLine();

        /** @var ExpenseLine $expenseLine */
        $expenseLine = $this->dtoToEntityHelper->create($payload, $expenseLine);

//        if (isset($category)) {
//            $expenseLine->setCategory($category);
//        }

        $this->expenseLineRepository->save($expenseLine, true);

        return $expenseLine;
    }
}
