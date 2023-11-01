<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Context\Normalizer\ObjectNormalizerContextBuilder;

class BaseRestController extends AbstractController
{
    public function ApiResponse(
        mixed $data,
        array $groups = [],
        ObjectNormalizerContextBuilder $context = new ObjectNormalizerContextBuilder(),
        int $status = Response::HTTP_OK,
    ): JsonResponse {
        $responseContext = $context
            ->withGroups([...$groups]);

        return $this->json(data: $data, status: $status, context: $responseContext->toArray());
    }
}
