<?php

declare(strict_types=1);

namespace App\Shared\Api;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

/**
 * @phpstan-type ArrayHeaders array<string, string>
 * @phpstan-type ArrayContext array<mixed>
 */
class AbstractApiController extends AbstractController
{
    /**
     * @param ArrayHeaders $headers
     * @param ArrayContext $context
     */
    private function jsonResponse(
        mixed $data,
        int $status = 200,
        array $headers = [],
        array $context = [],
    ): JsonResponse {
        return $this->json(data: $data, status: $status, headers: $headers, context: $context);
    }

    /**
     * @param ArrayHeaders $headers
     * @param ArrayContext $context
     */
    protected function successResponse(
        mixed $data,
        mixed $meta = null,
        array $headers = [],
        array $context = [],
        int $status = Response::HTTP_OK
    ): JsonResponse {
        $response = [
            'data' => $data,
        ];

        if ($meta !== null) {
            $response['meta'] = $meta;
        }

        return $this->jsonResponse(
            data: $response['data'],
            status: $status,
            headers: $headers,
            context: $context,
        );
    }

    public function noContentResponse(): JsonResponse
    {
        return $this->json(data: null, status: 204);
    }
}
