<?php

namespace App\Tests\Common\Helper;

use Exception;
use PHPUnit\Framework\MockObject\MockObject;

trait MockHelperTrait
{
    /**
     * @template T of object
     *
     * @param class-string<T> $class
     *
     * @return MockObject&T
     * @throws Exception
     */
    public function createMockAndSetToContainer(string $class): MockObject
    {
        $mock = $this->createMock($class);
        $this->getContainer()->set($class, $mock);

        return $mock;
    }
}
