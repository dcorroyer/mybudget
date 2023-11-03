<?php

declare(strict_types=1);

namespace App\Utils;

use Symfony\Component\Serializer\Context\ContextBuilderInterface;
use Symfony\Component\Serializer\Context\Normalizer\ObjectNormalizerContextBuilder;

final class ObjectContextMerger
{
    public static function mergeContext(
        array|ContextBuilderInterface ...$contexts
    ): ObjectNormalizerContextBuilder {
        $globalContext = [];
        foreach ($contexts as $context) {
            if ($context instanceof ContextBuilderInterface) {
                $context = $context->toArray();
            }

            $globalContext = array_merge_recursive($globalContext, $context);
        }

        return (new ObjectNormalizerContextBuilder())->withContext($globalContext);
    }
}
