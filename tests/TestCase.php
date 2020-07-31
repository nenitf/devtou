<?php

namespace Tests;

class TestCase extends \PHPUnit\Framework\TestCase {
    protected function new(string $fullClassName, ?array $methodsToMock = null, ?array $constructorArgs = [])
    {
        return $this
            ->getMockBuilder($fullClassName)
            ->setConstructorArgs($constructorArgs)
            ->setMethods($methodsToMock)
            ->getMock();
    }

    // THANKS: https://stackoverflow.com/a/2798203/9881278
    protected function invokeProtectedMethod(object $object, string $methodName, ?array $args = []) {
        $method = new \ReflectionMethod(get_class($object), $methodName);
        $method->setAccessible(true);
        return $method->invokeArgs($object, $args);
    }
}
