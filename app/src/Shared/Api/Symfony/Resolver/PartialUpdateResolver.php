<?php

declare(strict_types=1);

namespace App\Shared\Api\Symfony\Resolver;

use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Attribute\MapQueryString;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\HttpKernel\Attribute\MapUploadedFile;
use Symfony\Component\HttpKernel\Controller\ArgumentResolver\RequestPayloadValueResolver;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;
use Symfony\Component\HttpKernel\Event\ControllerArgumentsEvent;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\UnsupportedMediaTypeHttpException;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Serializer\Exception\NotEncodableValueException;
use Symfony\Component\Serializer\Exception\PartialDenormalizationException;
use Symfony\Component\Serializer\Exception\UnexpectedPropertyException;
use Symfony\Component\Serializer\Exception\UnsupportedFormatException;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Constraints\All;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\ConstraintViolationList;
use Symfony\Component\Validator\Exception\ValidationFailedException;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class PartialUpdateResolver extends RequestPayloadValueResolver
{
    /**
     * @see \Symfony\Component\Serializer\Normalizer\AbstractObjectNormalizer::DISABLE_TYPE_ENFORCEMENT
     * @see DenormalizerInterface::COLLECT_DENORMALIZATION_ERRORS
     */
    private const array CONTEXT_DENORMALIZE = [
        'disable_type_enforcement' => true,
        'collect_denormalization_errors' => true,
    ];

    /**
     * @see DenormalizerInterface::COLLECT_DENORMALIZATION_ERRORS
     */
    private const array CONTEXT_DESERIALIZE = [
        'collect_denormalization_errors' => true,
    ];

    public function __construct(
        private readonly SerializerInterface&DenormalizerInterface $serializer,
        private readonly ?ValidatorInterface $validator = null,
        private readonly ?TranslatorInterface $translator = null,
        private readonly string $translationDomain = 'validators',
    ) {
        parent::__construct($serializer, $validator, $translator, $translationDomain);
    }

    #[\Override]
    public function onKernelControllerArguments(ControllerArgumentsEvent $event): void
    {
        $arguments = $event->getArguments();

        foreach ($arguments as $i => $argument) {
            $isPatch = $event->getRequest()->getMethod() === 'PATCH';
            if ($argument instanceof MapQueryString) {
                $payloadMapper = $this->mapQueryString(...);
                $validationFailedCode = $argument->validationFailedStatusCode;
            } elseif ($argument instanceof MapRequestPayload) {
                $payloadMapper = $this->mapRequestPayload(...);
                $validationFailedCode = $argument->validationFailedStatusCode;
            } elseif ($argument instanceof MapUploadedFile) {
                $payloadMapper = $this->mapUploadedFile(...);
                $validationFailedCode = $argument->validationFailedStatusCode;
            } else {
                continue;
            }

            $request = $event->getRequest();

            if (! $argument->metadata->getType()) {
                throw new \LogicException(\sprintf(
                    'Could not resolve the "$%s" controller argument: argument should be typed.',
                    $argument->metadata->getName()
                ));
            }

            if ($this->validator instanceof ValidatorInterface) {
                $violations = new ConstraintViolationList();
                try {
                    $payload = $payloadMapper($request, $argument->metadata, $argument);
                } catch (PartialDenormalizationException $e) {
                    $trans = $this->translator instanceof TranslatorInterface ? $this->translator->trans(
                        ...
                    ) : static fn ($m, $p): string => strtr($m, $p);
                    foreach ($e->getErrors() as $error) {
                        $parameters = [];
                        $template = 'This value was of an unexpected type.';
                        if ($expectedTypes = $error->getExpectedTypes()) {
                            $template = 'This value should be of type {{ type }}.';
                            $parameters['{{ type }}'] = implode('|', $expectedTypes);
                        }

                        if ($error->canUseMessageForUser()) {
                            $parameters['hint'] = $error->getMessage();
                        }

                        $message = $trans($template, $parameters, $this->translationDomain);
                        $violations->add(
                            new ConstraintViolation($message, $template, $parameters, null, $error->getPath(), null),
                        );
                    }

                    $payload = $e->getData();
                }

                if ($payload !== null && \count($violations) === 0) {
                    $constraints = $argument->constraints ?? null;
                    if (\is_array($payload) && ! empty($constraints) && ! $constraints instanceof All) {
                        $constraints = new All($constraints);
                    }

                    if ($isPatch) {
                        $reflectionClass = new \ReflectionClass($payload);
                        foreach ($reflectionClass->getProperties() as $property) {
                            if ($property->isInitialized($payload) === false) {
                                continue;
                            }

                            $violations->addAll(
                                $this->validator->validateProperty(
                                    $payload,
                                    $property->getName(),
                                    $argument->validationGroups ?? null,
                                ),
                            );
                        }
                    } else {
                        $violations->addAll(
                            $this->validator->validate($payload, $constraints, $argument->validationGroups ?? null),
                        );
                    }
                }

                if (\count($violations) > 0) {
                    throw HttpException::fromStatusCode(
                        $validationFailedCode,
                        implode(
                            "\n",
                            array_map(static fn ($e): string|\Stringable => $e->getMessage(), iterator_to_array(
                                $violations
                            ))
                        ),
                        new ValidationFailedException($payload, $violations)
                    );
                }
            } else {
                try {
                    $payload = $payloadMapper($request, $argument->metadata, $argument);
                } catch (PartialDenormalizationException $e) {
                    throw HttpException::fromStatusCode(
                        $validationFailedCode,
                        implode("\n", array_map(static fn ($e): string => $e->getMessage(), $e->getErrors())),
                        $e
                    );
                }
            }

            if ($payload === null) {
                $payload = match (true) {
                    $argument->metadata->hasDefaultValue() => $argument->metadata->getDefaultValue(),
                    $argument->metadata->isNullable() => null,
                    default => throw HttpException::fromStatusCode($validationFailedCode),
                };
            }

            $arguments[$i] = $payload;
        }

        $event->setArguments($arguments);
    }

    #[\Override]
    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::CONTROLLER_ARGUMENTS => 'onKernelControllerArguments',
        ];
    }

    private function mapQueryString(Request $request, ArgumentMetadata $argument, MapQueryString $attribute): ?object
    {
        if (($data = $request->query->all()) === [] && ($argument->isNullable() || $argument->hasDefaultValue())) {
            return null;
        }

        return $this->serializer->denormalize(
            $data,
            $argument->getType(),
            null,
            $attribute->serializationContext + self::CONTEXT_DENORMALIZE + [
                'filter_bool' => true,
            ],
        );
    }

    private function mapRequestPayload(
        Request $request,
        ArgumentMetadata $argument,
        MapRequestPayload $attribute,
    ): object|array|null {
        if (null === $format = $request->getContentTypeFormat()) {
            throw new UnsupportedMediaTypeHttpException('Unsupported format.');
        }

        if ($attribute->acceptFormat && ! \in_array($format, (array) $attribute->acceptFormat, true)) {
            throw new UnsupportedMediaTypeHttpException(\sprintf(
                'Unsupported format, expects "%s", but "%s" given.',
                implode('", "', (array) $attribute->acceptFormat),
                $format
            ));
        }

        $type = $argument->getType() === 'array' && $attribute->type !== null ? $attribute->type . '[]' : $argument->getType();

        if (($data = $request->request->all()) !== []) {
            return $this->serializer->denormalize(
                $data,
                $type,
                null,
                $attribute->serializationContext + self::CONTEXT_DENORMALIZE + ($format === 'form' ? [
                    'filter_bool' => true,
                ] : []),
            );
        }

        if ('' === ($data = $request->getContent()) && ($argument->isNullable() || $argument->hasDefaultValue())) {
            return null;
        }

        if ($format === 'form') {
            throw new BadRequestHttpException('Request payload contains invalid "form" data.');
        }

        try {
            return $this->serializer->deserialize(
                $data,
                $type,
                $format,
                self::CONTEXT_DESERIALIZE + $attribute->serializationContext,
            );
        } catch (UnsupportedFormatException $e) {
            throw new UnsupportedMediaTypeHttpException(\sprintf('Unsupported format: "%s".', $format), $e);
        } catch (NotEncodableValueException $e) {
            throw new BadRequestHttpException(\sprintf('Request payload contains invalid "%s" data.', $format), $e);
        } catch (UnexpectedPropertyException $e) {
            throw new BadRequestHttpException(\sprintf(
                'Request payload contains invalid "%s" property.',
                $e->property
            ), $e);
        }
    }

    private function mapUploadedFile(
        Request $request,
        ArgumentMetadata $argument,
        MapUploadedFile $attribute,
    ): UploadedFile|array|null {
        return $request->files->get($attribute->name ?? $argument->getName(), []);
    }
}
