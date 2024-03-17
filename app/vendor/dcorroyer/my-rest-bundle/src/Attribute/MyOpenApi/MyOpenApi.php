<?php

declare(strict_types=1);

namespace My\RestBundle\Attribute\MyOpenApi;

use My\RestBundle\Attribute\MyOpenApi\Response\ErrorResponse;
use My\RestBundle\Enum\ApiResponseStatuses;
use My\RestBundle\Serialization\ApiSerializationGroups;
use InvalidArgumentException;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Annotations\Operation;
use OpenApi\Annotations\PathItem;
use OpenApi\Attributes as OA;
use OpenApi\Attributes\Schema;
use OpenApi\Generator;
use ReflectionAttribute;
use ReflectionClass;
use ReflectionException;
use ReflectionIntersectionType;
use ReflectionNamedType;
use ReflectionProperty;
use ReflectionUnionType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use function current;

/**
 * Represents an OpenAPI operation for My API
 *
 * The representation of these objects is to commonize the OpenAPI documentation of the API.
 */
#[\Attribute(\Attribute::TARGET_METHOD)]
class MyOpenApi extends Operation
{
    use OA\OperationTrait;

    /**
     * @inheritdoc
     */
    public static $_parents = [
        PathItem::class,
    ];

    private const ALLOWED_QUERY_PARAMS_METHODS = [
        Request::METHOD_GET,
        Request::METHOD_POST,
    ];

    private const ALLOWED_REQUEST_BODY_METHODS = [
        Request::METHOD_POST,
        Request::METHOD_PUT,
        Request::METHOD_PATCH,
    ];

    private const ALLOWED_METHODS = [
        Request::METHOD_GET,
        Request::METHOD_POST,
        Request::METHOD_PUT,
        Request::METHOD_PATCH,
        Request::METHOD_DELETE,
    ];

    /**
     * Validate HTTP Method and Fqcn's for Query Param and Request Body.
     *
     * @param string $httpMethod
     * @param class-string|array<class-string>|null $queryParamsClassFqcn
     * @param string|null $requestBodyClassFqcn
     *
     * @throws InvalidArgumentException
     */
    private function validateMethod(string $httpMethod, string|array|null $queryParamsClassFqcn, ?string $requestBodyClassFqcn): void
    {
        $httpMethod = strtoupper($httpMethod);

        $this->validateHttpMethod($httpMethod, self::ALLOWED_METHODS, 'Method not supported');
        $this->validateHttpMethodWithClassFqcn(
            $httpMethod,
            $queryParamsClassFqcn,
            self::ALLOWED_QUERY_PARAMS_METHODS,
            'queryParamsClassFqcn can be used only with %s methods'
        );
        $this->validateHttpMethodWithClassFqcn(
            $httpMethod,
            $requestBodyClassFqcn, // @phpstan-ignore-line - $requestBodyClassFqcn cannot be an array here.
            self::ALLOWED_REQUEST_BODY_METHODS,
            'requestBodyClassFqcn can be used only with %s methods'
        );
    }

    /**
     * Validates the HTTP method is within allowable methods.
     *
     * @param string $httpMethod
     * @param array<string> $allowedMethods
     * @param string $errorMessage
     *
     * @throws InvalidArgumentException
     */
    private function validateHttpMethod(string $httpMethod, array $allowedMethods, string $errorMessage): void
    {
        if (!in_array($httpMethod, $allowedMethods, true)) {
            throw new InvalidArgumentException($errorMessage);
        }
    }

    /**
     * Validate the HTTP method against the given class fully qualified class name (FQCN).
     *
     * @param string $httpMethod The HTTP method to validate.
     * @param class-string|array<class-string>|null $classFqcn The fully qualified class name (FQCN) to validate against. Can be null.
     * @param array<string> $allowedMethods An array of allowed HTTP methods.
     * @param string $errorMessageFormat The format of the error message to throw if the validation fails.
     *
     * @throws InvalidArgumentException If the HTTP method is not in the allowed methods array and the class FQCN is not null.
     */
    private function validateHttpMethodWithClassFqcn(
        string            $httpMethod,
        string|array|null $classFqcn,
        array             $allowedMethods,
        string            $errorMessageFormat
    ): void {
        if ($classFqcn !== null && !in_array($httpMethod, $allowedMethods, true)) {
            throw new InvalidArgumentException(sprintf($errorMessageFormat, implode(', ', $allowedMethods)));
        }
    }

    /**
     * @param string $httpMethod
     * @param string $operationId
     * @param string $summary
     * @param array<int, MyOpenApiResponse|OA\Response|MyOpenApiResponseList> $responses
     * @param class-string|array<class-string>|null $queryParamsClassFqcn
     * @param class-string|null $requestBodyClassFqcn
     * @param array<string> $groups
     * @throws InvalidArgumentException
     * @throws ReflectionException
     */
    public function __construct(
        string            $httpMethod,
        string            $operationId,
        string            $summary,
        array             $responses,
        string|array|null $queryParamsClassFqcn = null,
        string            $requestBodyClassFqcn = null,
        array             $groups = [],
    ) {
        $this->validateMethod($httpMethod, $queryParamsClassFqcn, $requestBodyClassFqcn);
        $this->method = strtolower($httpMethod);
        $this->operationId = $operationId;
        $this->summary = $summary;

        if ($queryParamsClassFqcn) {
            if (is_string($queryParamsClassFqcn)) {
                $queryParamsClassFqcn = [$queryParamsClassFqcn];
            }
            $this->parameters = [];
            foreach ($queryParamsClassFqcn as $classFqcn) {
                $this->parameters = [...$this->parameters, ...$this->generateOpenApiParametersFromDtoAttributes($classFqcn)];
            }
        }

        if ($requestBodyClassFqcn) {
            $this->requestBody = $this->generateOpenApiRequestBodyFromDto($requestBodyClassFqcn, $groups);
        }

        $defaultResponses = [
            new ErrorResponse(responseCode: Response::HTTP_BAD_REQUEST, description: 'Bad request'),
        ];
        $this->responses = $this->getMergedHttpResponsesWithPrecedence($responses, $defaultResponses);

        parent::__construct([]);
    }

    /**
     * Builds an OA\Response object based on a MyOpenApiResponse object.
     *
     * @param MyOpenApiResponse $myOpenApiResponse The MyOpenApiResponse object to build the response from.
     * @return OA\Response The built OA\Response object.
     */
    private function buildResponse(MyOpenApiResponse $myOpenApiResponse): OA\Response
    {
        $isSuccess = $this->checkIfSuccessful($myOpenApiResponse->getResponseCode());
        $responseGroups = $this->getResponseGroups($isSuccess);
        $groups = $myOpenApiResponse->getGroups();

        $properties = [];
        $properties[] = $this->buildDataProperty($myOpenApiResponse, $groups);
        $properties[] = $this->buildMetaProperty($myOpenApiResponse, $responseGroups);
        $properties[] = $this->buildStatusProperty($isSuccess);
        $properties[] = $this->buildMessageProperty();

        $fallBackDescription = $this->getFallbackDescription($myOpenApiResponse);

        return new OA\Response(
            response: $myOpenApiResponse->getResponseCode(),
            description: !empty(trim($myOpenApiResponse->getDescription())) ? $myOpenApiResponse->getDescription() : $fallBackDescription,
            content: $this->buildJsonContent($myOpenApiResponse, $properties)
        );
    }

    /**
     * Checks if the response code indicates a successful operation
     *
     * @param int $responseCode The response code to check
     * @return bool True if the response code is between 200 and 299 (inclusive), false otherwise
     */
    private function checkIfSuccessful(int $responseCode): bool
    {
        return $responseCode >= 200 && $responseCode < 300;
    }

    /**
     * Get the response groups based on the success flag
     *
     * @param bool $isSuccess A flag indicating if the response is successful or not.
     * @return array<string> An array of response groups.
     */
    private function getResponseGroups(bool $isSuccess): array
    {
        return $isSuccess ?
            [ApiSerializationGroups::API_SUCCESS]
            : [ApiSerializationGroups::API_ERROR, ApiSerializationGroups::API_ERROR_CODE];
    }

    /**
     * Build the "data" OA\Property for the given MyOpenApiResponse
     *
     * @param MyOpenApiResponse $myOpenApiResponse
     * @param array<string> $groups
     * @return OA\Property
     */
    private function buildDataProperty(MyOpenApiResponse $myOpenApiResponse, array $groups): OA\Property
    {
        if ($myOpenApiResponse->isAsArray()) {
            return new OA\Property(
                property: 'data',
                type: 'array',
                items: new OA\Items(
                    ref: new Model(
                        type: $myOpenApiResponse->getResponseClassFqcn(),
                        groups: $groups !== [] ? $groups : null
                    )
                )
            );
        } else {
            return new OA\Property(
                property: 'data',
                ref: new Model(
                    type: $myOpenApiResponse->getResponseClassFqcn(),
                    groups: $groups !== [] ? $groups : null
                ),
                type: 'object'
            );
        }
    }

    /**
     * Build the "meta" OA\Property for a given response
     *
     * @param MyOpenApiResponse $myOpenApiResponse The response object
     * @param array<string> $responseGroups The response groups
     * @return OA\Property The built "meta" property
     */
    private function buildMetaProperty(
        MyOpenApiResponse $myOpenApiResponse,
        array             $responseGroups
    ): OA\Property {
        return new OA\Property(
            property: 'meta',
            ref: $myOpenApiResponse->getMetaClassFqcn() !== null ?
                new Model(
                    type: $myOpenApiResponse->getMetaClassFqcn(),
                    groups: $responseGroups
                )
                : null,
            type: 'object',
        );
    }

    /**
     * Build the "status" OA\Property for the API response
     *
     * @param bool $isSuccess Specifies if the response is a success or not
     * @return OA\Property The built status property
     */
    private function buildStatusProperty(bool $isSuccess): OA\Property
    {
        return new OA\Property(
            property: 'status',
            type: 'string',
            example: $isSuccess ? ApiResponseStatuses::STATUS_SUCCESS : ApiResponseStatuses::STATUS_ERROR
        );
    }

    /**
     * Build the "message" OA\Property for the API response
     *
     * @return OA\Property
     */
    private function buildMessageProperty(): OA\Property
    {
        return new OA\Property(property: 'message', type: 'string');
    }

    /**
     * Get the fallback description for a given MyOpenApiResponse
     *
     * @param MyOpenApiResponse $myOpenApiResponse The MyOpenApiResponse object
     * @return string The fallback description
     */
    private function getFallbackDescription(MyOpenApiResponse $myOpenApiResponse): string
    {
        return sprintf(
            '%s Response for %s',
            Response::$statusTexts[$myOpenApiResponse->getResponseCode()] ?? 'Unknown',
            $myOpenApiResponse->getResponseCode(),
        );
    }

    /**
     * Build the JSON content for a given MyOpenApiResponse
     *
     * @param MyOpenApiResponse $myOpenApiResponse The response object to build the JSON content for
     * @param array<OA\Property> $properties An array of properties for the JSON content
     * @return OA\JsonContent The JSON content object
     */
    private function buildJsonContent(MyOpenApiResponse $myOpenApiResponse, array $properties): OA\JsonContent
    {
        return new OA\JsonContent(
            description: $myOpenApiResponse->getDescription(),
            properties: $properties,
            type: 'object',
        );
    }

    /**
     * Retrieve parameters from the given DTO.
     *
     * @param ?class-string $queryParamsClassFqcn The fully qualified class name of the query parameters DTO.
     * @return array<OA\Parameter> An array of OA\Parameter objects representing the parameters extracted from the DTO.
     * @throws InvalidArgumentException If the DTO does not have any properties.
     * @throws ReflectionException If the DTO class does not exist.
     */
    private function generateOpenApiParametersFromDtoAttributes(?string $queryParamsClassFqcn): array
    {
        $reflection = new ReflectionClass($queryParamsClassFqcn);
        $properties = $reflection->getProperties();
        $parameters = [];

        foreach ($properties as $property) {
            $this->ensurePropertyHasType($property, $queryParamsClassFqcn);
            $type = $this->determineType($property);

            $parameterAttributes = $property->getAttributes(OA\Parameter::class);

            $this->checkAttributeErrors($parameterAttributes, $property, $type->getName());

            /** @var false|ReflectionAttribute $existingAttribute */
            $existingAttribute = current($property->getAttributes(OA\Parameter::class));
            if ($existingAttribute === false) {
                $parameters[] = new OA\Parameter(
                    name: $property->getName(),
                    in: 'query',
                    schema: $this->generateSchema($type->getName(), $type->isBuiltInType(), $type->getFormat()),
                );
                continue;
            }

            $args = $existingAttribute->getArguments();
            $parameters[] = new OA\Parameter(
                name: $property->getName(),
                description: $args['description'] ?? null,
                in: $args['in'] ?? 'query',
                required: $args['required'] ?? null,
                deprecated: $args['deprecated'] ?? null,
                allowEmptyValue: $args['allowEmptyValue'] ?? null,
                ref: $args['ref'] ?? null,
                schema: $args['schema'] ?? $this->generateSchema($type->getName(), $type->isBuiltInType(), $type->getFormat()),
                example: $args['example'] ?? Generator::UNDEFINED,
                examples: $args['examples'] ?? null,
                content: $args['content'] ?? null,
                style: $args['style'] ?? null,
                explode: $args['explode'] ?? null,
                allowReserved: $args['allowReserved'] ?? null,
                spaceDelimited: $args['spaceDelimited'] ?? null,
                pipeDelimited: $args['pipeDelimited'] ?? null,
                x: $args['x'] ?? null,
                attachables: $args['attachables'] ?? null,
            );
        }

        return $parameters;
    }

    /**
     * Ensures that a property has a type.
     *
     * @param ReflectionProperty $property The property to check.
     * @param class-string $queryParamsClassFqcn The fully qualified class name of the query parameters class.
     *
     * @throws InvalidArgumentException If the property does not have a type.
     */
    private function ensurePropertyHasType(ReflectionProperty $property, string $queryParamsClassFqcn): void
    {
        $type = $property->getType();

        if ($type !== null) {
            return;
        }

        throw new InvalidArgumentException("Parameter {$property->getName()} in class $queryParamsClassFqcn has no type. Is required to generate OpenAPI documentation.");
    }

    /**
     * Determine the data type of the given property.
     *
     * @param ReflectionProperty $property The property to determine the type of.
     *
     * @return CustomOpenApiType The data type of the property.
     */
    private function determineType(ReflectionProperty $property): CustomOpenApiType
    {
        $type = $property->getType();

        if ($type instanceof ReflectionUnionType || $type instanceof ReflectionIntersectionType) {
            $delimiter = $type instanceof ReflectionUnionType ? '|' : '&';
            $types = array_map(fn (ReflectionNamedType|ReflectionIntersectionType $type) => $type instanceof ReflectionNamedType ? $type->getName() : $this->determineType($property)->getName(), $type->getTypes());
            return new CustomOpenApiType(implode($delimiter, $types), null, true);
        } else {
            /** @var ReflectionNamedType $type */
            // Handle the DateTime type to return a string with the format "date-time".
            if (in_array($type->getName(), ['DateTime', 'DateTimeImmutable', 'DateTimeInterface'], true)) {
                return new CustomOpenApiType('string', 'date-time', true);
            }

            $type = $type->getName() === 'array' ? "array" : $type->getName();
            $format = null;
            $isBuiltInType = $this->isBuiltInType($property);

            return new CustomOpenApiType($type, $format, $isBuiltInType);
        }
    }

    /**
     * Format a PHP type to be compatible with OpenAPI.
     *
     * @param string $type The PHP type to format.
     * @return string The formatted OpenAPI compatible type.
     */
    private function formatPhpTypeToOpenApiCompatible(string $type): string
    {
        return match ($type) {
            'int' => 'integer',
            'bool' => 'boolean',
            'float' => 'number',
            default => $type,
        };
    }

    private function generateSchema(string $type, bool $isBuiltInType, ?string $format): OA\Schema
    {
        return $this->generateOASchema($isBuiltInType, $this->formatPhpTypeToOpenApiCompatible($type), $format);
    }

    /**
     * Generate an OpenAPI schema for the given property.
     *
     * @param bool $isBuiltIn Whether the property is a built-in type.
     * @param string $type The data type of the property.
     *
     * @return Schema The OpenAPI schema representing the property.
     */
    private function generateOASchema(bool $isBuiltIn, string $type, ?string $format): OA\Schema
    {
        return new OA\Schema(
            ref: $isBuiltIn && !class_exists($type) ? null : new Model(type: $type),
            type: $type,
            format: $format
        );
    }

    /**
     * Check for if the parameter has more than one OpenAPI parameter attribute or no OpenAPI parameter attribute.
     *
     * @param array<ReflectionAttribute> $parameterAttributes
     * @throws InvalidArgumentException
     */
    private function checkAttributeErrors(array $parameterAttributes, ReflectionProperty $property, string $type): void
    {
        if (count($parameterAttributes) > 1) {
            throw new InvalidArgumentException(
                sprintf(
                    'Property %s in class %s has more than one OpenAPI parameter (OA\Parameter) attribute.',
                    $property->getName(),
                    $property->getDeclaringClass()->getName()
                )
            );
        }

        if (current($parameterAttributes) === false && $type === 'array') {
            throw new InvalidArgumentException(
                sprintf(
                    'Property %s in class %s has no OpenAPI parameter (OA\Parameter) attribute. (Array type parameters must have an OpenAPI parameter attribute.)',
                    $property->getName(),
                    $property->getDeclaringClass()->getName()
                )
            );
        }
    }

    /**
     * Creating a request body from a DTO.
     *
     * @param class-string $requestBodyClassFqcn
     * @param array<string> $groups
     * @return OA\RequestBody
     *
     * @throws InvalidArgumentException
     */
    private function generateOpenApiRequestBodyFromDto(string $requestBodyClassFqcn, array $groups): OA\RequestBody
    {
        if (!class_exists($requestBodyClassFqcn)) {
            throw new InvalidArgumentException(sprintf('Class "%s" does not exist.', $requestBodyClassFqcn));
        }
        return new OA\RequestBody(
            description: 'Payload for request',
            required: true,
            content: new OA\JsonContent(
                ref: new Model(type: $requestBodyClassFqcn, groups: $groups !== [] ? $groups : null),
            )
        );
    }

    /**
     * Merge HTTP responses with precedence given to the non-default responses.
     *
     * @param array<MyOpenApiResponse|MyOpenApiResponseList|OA\Response> $responses The array of responses to merge.
     * @param array<MyOpenApiResponse|MyOpenApiResponseList|OA\Response> $defaultResponses The array of default responses to merge.
     *
     * @return array<OA\Response> The merged array of responses with precedence given to the non-default responses.
     */
    private function getMergedHttpResponsesWithPrecedence(array $responses, array $defaultResponses): array
    {
        $responses = $this->formatResponses($responses);
        $defaultResponses = $this->formatResponses($defaultResponses);

        $alreadyPresentCodes = array_intersect_key($responses, $defaultResponses);

        $defaultResponses = array_diff_key($defaultResponses, $alreadyPresentCodes);

        return array_merge($responses, $defaultResponses);
    }


    /**
     * Format different possible entry types to a single type of OA\Response
     *
     * @param array<MyOpenApiResponse|MyOpenApiResponseList|OA\Response> $responses
     * @return array<OA\Response>
     */
    private function formatResponses(array $responses): array
    {
        $formattedResponses = [];
        foreach ($responses as $response) {
            if ($response instanceof MyOpenApiResponse) {
                $formattedResponses[$response->getResponseCode()] = $this->buildResponse($response);
            } elseif ($response instanceof OA\Response) {
                $formattedResponses[$response->response] = $response;
            } elseif ($response instanceof MyOpenApiResponseList) {
                foreach ($response->getResponses() as $resp) {
                    if ($resp instanceof OA\Response) {
                        $formattedResponses[$resp->response] = $resp;
                        continue;
                    }
                    $formattedResponses[$resp->getResponseCode()] = $this->buildResponse($resp);
                }
            }
        }

        return $formattedResponses;
    }

    private function isBuiltInType(ReflectionProperty $property): bool
    {
        $type = $property->getType();
        if ($type === null) {
            return false;
        }

        $isBuiltInType = false;
        if ($type instanceof ReflectionNamedType) {
            $isBuiltInType = $type->isBuiltin();
        } elseif ($type instanceof ReflectionUnionType) {
            $isBuiltInType = true;
            foreach ($type->getTypes() as $unionType) {
                if (!$unionType->isBuiltin()) {
                    $isBuiltInType = false;
                }
            }
        } elseif ($type instanceof ReflectionIntersectionType) {
            $isBuiltInType = true;
            foreach ($type->getTypes() as $intersectionType) {
                if (!$intersectionType->isBuiltin()) {
                    $isBuiltInType = false;
                }
            }
        }

        return $isBuiltInType;
    }
}
