<?php

declare(strict_types=1);

namespace App\Tests\Common\Helper;

use PHPUnit\Framework\MockObject\MockObject;

trait MockHelperTrait
{
    /**
     * @template T of object
     *
     * @param class-string<T> $class
     *
     * @return MockObject&T
     */
    public function createMockAndSetToContainer(string $class): MockObject
    {
        $mock = $this->createMock($class);
        $this->getContainer()
            ->set($class, $mock)
        ;

        return $mock;
    }
}
