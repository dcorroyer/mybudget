<?php

declare(strict_types=1);

namespace My\RestBundle\Serialization\Model;

use My\RestBundle\Enum\ApiResponseStatuses;
use My\RestBundle\Serialization\ApiSerializationGroups;
use OpenApi\Attributes as OA;
use Symfony\Component\Serializer\Annotation\Groups;

#[OA\Schema()]
final class ApiResponse
{
    /**
     *  The response errors
     *  @var array<mixed> $errors
     */
    #[OA\Property(type: 'array', items: new OA\Items())]
    #[Groups([ApiSerializationGroups::API_ERROR])]
    private array $errors = [];

    /**
     * The response data
     */
    #[OA\Property(type: 'array', items: new OA\Items())]
    #[Groups([ApiSerializationGroups::API_SUCCESS])]
    private mixed $data = null;

    /**
     * The response metadata
     */
    #[OA\Property(type: 'array', items: new OA\Items())]
    #[Groups([ApiSerializationGroups::API_SUCCESS])]
    private mixed $meta = null;

    /**
     * The response status
     */
    #[OA\Property(enum: [ApiResponseStatuses::STATUS_SUCCESS->name, ApiResponseStatuses::STATUS_ERROR->name])]
    #[Groups([ApiSerializationGroups::API_SUCCESS, ApiSerializationGroups::API_ERROR])]
    private ApiResponseStatuses $status = ApiResponseStatuses::STATUS_SUCCESS;

    /**
     * The response context
     */
    #[OA\Property()]
    #[Groups([ApiSerializationGroups::API_ERROR])]
    private string $message = '';

    #[Groups([ApiSerializationGroups::API_ERROR_CODE])]
    private string $code = '';

    /**
     * @param mixed[] $errors
     */
    public function __construct(
        mixed $data = null,
        mixed $meta = null,
        array $errors = [],
        string $message = '',
        ApiResponseStatuses $status = ApiResponseStatuses::STATUS_SUCCESS,
        string $code = '',
    ) {
        $this->data = $data;
        $this->meta = $meta;
        $this->errors = $errors;
        $this->message = $message;
        $this->status = $status;
        $this->code = $code;
    }

    /**
     * @return mixed[]
     */
    public function getErrors(): array
    {
        return $this->errors;
    }

    /**
     * @param mixed[] $errors
     */
    public function setErrors(array $errors): self
    {
        $this->errors = $errors;

        return $this;
    }

    public function getData(): mixed
    {
        return $this->data;
    }

    public function setData(mixed $data): self
    {
        $this->data = $data;

        return $this;
    }

    public function getMeta(): mixed
    {
        return $this->meta;
    }

    public function setMeta(mixed $meta): self
    {
        $this->meta = $meta;

        return $this;
    }

    public function getMessage(): string
    {
        return $this->message;
    }

    public function setMessage(string $message = ''): self
    {
        $this->message = $message;

        return $this;
    }

    public function getStatus(): ApiResponseStatuses
    {
        return $this->status;
    }

    public function setStatus(ApiResponseStatuses $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getCode(): string
    {
        return $this->code;
    }

    public function setCode(string $code): void
    {
        $this->code = $code;
    }
}
