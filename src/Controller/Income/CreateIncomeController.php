<?php

declare(strict_types=1);

namespace App\Controller\Income;

use App\Controller\BaseRestController;
use App\Dto\Income\Payload\IncomePayload;
use App\Serializable\SerializationGroups;
use App\Service\IncomeService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/incomes')]
class CreateIncomeController extends BaseRestController
{
    public function __construct(
        private readonly IncomeService $incomeService,
    ) {
    }

    #[Route('', name: 'app_incomes_create', methods: 'POST')]
    public function create(#[MapRequestPayload] IncomePayload $incomePayload): JsonResponse
    {
        $income = $this->incomeService->create($incomePayload);

        return $this->ApiResponse(
            data: $income,
            groups: [SerializationGroups::INCOME_CREATE],
            status: Response::HTTP_CREATED,
        );
    }
}
