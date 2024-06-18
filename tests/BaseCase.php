<?php

declare(strict_types=1);

namespace SuareSu\PyrusClientSymfony\Tests;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 * Base test case for all tests.
 *
 * @internal
 *
 * @psalm-api
 */
abstract class BaseCase extends TestCase
{
    /**
     * @template T
     *
     * @param class-string<T> $className
     *
     * @return MockObject&T
     */
    protected function mock(string $className): MockObject
    {
        $mock = $this->getMockBuilder($className)
            ->disableOriginalConstructor()
            ->getMock();

        return $mock;
    }
}
