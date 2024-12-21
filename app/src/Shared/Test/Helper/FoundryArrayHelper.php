<?php

declare(strict_types=1);

namespace App\Shared\Test\Helper;

use Zenstruck\Foundry\Persistence\Proxy;

class FoundryArrayHelper
{
    /**
     * @param array<Proxy|object>|null $items
     *
     * @return array<object>
     */
    public static function convertProxyToObject(array $items): array
    {
        return array_map(static fn ($item) => $item instanceof Proxy ? $item->object() : $item, $items);
    }
}
