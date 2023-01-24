<?php
namespace Test\Fixtures;

use PHPUnit\Framework\Assert;

class Functions
{
    public static function constant($value): callable
    {
        return static function () use ($value) {
            return $value;
        };
    }

    public static function toUpper(): callable
    {
        return static function (string $argument): string {
            return \strToUpper($argument);
        };
    }

    public static function throws(): callable
    {
        return static function (): void {
            throw new \AssertionError("Failed to assert that callable wasn't called");
        };
    }

    public static function out(&$argument, $return = null): callable
    {
        $wasCaptured = false;
        return function ($capturedArgument) use (&$wasCaptured, &$argument, $return) {
            if ($wasCaptured) {
                return $return;
            }
            $argument = $capturedArgument;
            $wasCaptured = true;
            return $return;
        };
    }

    public static function assertInstanceOf(string $className, $return = null): callable
    {
        return static function ($argument) use ($className, $return) {
            Assert::assertInstanceOf($className, $argument);
            return $return;
        };
    }
}
