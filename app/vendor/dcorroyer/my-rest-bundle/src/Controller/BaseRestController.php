<?php

declare(strict_types=1);

namespace My\RestBundle\Controller;

use My\RestBundle\Enum\ApiResponseStatuses;
use My\RestBundle\Enum\ErrorCodes;
use My\RestBundle\Serialization\ApiSerializationGroups;
use My\RestBundle\Utils\ObjectContextMerger;
use Knp\Bundle\PaginatorBundle\Pagination\SlidingPagination;
use My\RestBundle\Serialization\Model\ApiResponse;
use My\RestBundle\Serialization\Model\PaginatedListMetadata;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Serializer\Context\Normalizer\ObjectNormalizerContextBuilder;

class BaseRestController extends AbstractController
{
    public function successResponse(
        mixed $data,
        array $groups = [],
        ObjectNormalizerContextBuilder $context = new ObjectNormalizerContextBuilder(),
        array|object $meta = [],
        int $status = Response::HTTP_OK,
    ): JsonResponse {
        $apiResponse = new ApiResponse(data: $data, meta: $meta);

        $responseContext = $context
            ->withGroups([...$groups, ApiSerializationGroups::API_SUCCESS]);

        return $this->json(
            data: $apiResponse,
            status: $status,
            context: ObjectContextMerger::mergeContext($context, $responseContext)->toArray()
        );
    }

    public function paginateResponse(SlidingPagination $pagination, array $groups): JsonResponse
    {
        /** @var array<object> $data */
        $data = $pagination->getItems();

        $page = $pagination->getCurrentPageNumber();
        $itemsPerPage = $pagination->getItemNumberPerPage();
        $total = $pagination->getTotalItemCount();
        $firstItem = count($data) > 0 ? ($page - 1) * $itemsPerPage + 1 : 0;
        $lastItem = count($data) > 0 ? $firstItem + count($data) - 1 : 0;
        $hasMore = $itemsPerPage !== -1 && $total > ($itemsPerPage * $page);

        $meta = (new PaginatedListMetadata())
            ->setCurrentPage($page)
            ->setPerPage($itemsPerPage)
            ->setFrom($firstItem)
            ->setTo($lastItem)
            ->setTotal($total)
            ->setHasMore($hasMore);

        return $this->successResponse(data: $data, groups: $groups, meta: $meta);
    }

    protected function errorResponse(
        string $message = '',
        array $errors = [],
        int $responseCode = Response::HTTP_BAD_REQUEST,
        ObjectNormalizerContextBuilder $context = new ObjectNormalizerContextBuilder(),
        ?string $code = null,
    ): JsonResponse {
        $apiResponse = new ApiResponse(
            data: null,
            errors: $errors,
            message: $message,
            status: ApiResponseStatuses::STATUS_ERROR
        );

        $defaultGroups = [ApiSerializationGroups::API_ERROR];
        if ($code !== null) {
            $apiResponse->setCode($code);
            $defaultGroups[] = ApiSerializationGroups::API_ERROR_CODE;
        }

        $responseContext = $context
            ->withGroups([...$defaultGroups]);

        return $this->json(
            data: $apiResponse,
            status: $responseCode,
            context: (array) ObjectContextMerger::mergeContext($context, $responseContext)
        );
    }

    protected function notFoundResponse(
        bool $thrown = false
    ): JsonResponse {
        $message = ErrorCodes::NOT_FOUND->value;
        if ($thrown) {
            throw new NotFoundHttpException($message);
        }
        return $this->errorResponse(message: $message, errors: [ErrorCodes::NOT_FOUND], responseCode: Response::HTTP_NOT_FOUND);
    }

    protected function createNoContentResponse(): Response
    {
        return new Response(null, Response::HTTP_NO_CONTENT);
    }

    protected function createUnprocessableResponse(?string $message = null): Response
    {
        return new Response($message ?? 'Unprocessable error', Response::HTTP_UNPROCESSABLE_ENTITY);
    }
}
