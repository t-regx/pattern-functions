<?php
namespace Test\Fixtures;

use PHPUnit\Framework\Assert;

trait TestCasePasses
{
    public function pass(): void
    {
        Assert::assertTrue(true);
    }
}
