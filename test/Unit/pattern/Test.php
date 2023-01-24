<?php
namespace Test\Unit\pattern;

use PHPUnit\Framework\TestCase;
use Test\Fixtures\TestCasePasses;
use TRegx\CleanRegex\Pattern;

class Test extends TestCase
{
    use TestCasePasses;

    /**
     * @test
     */
    public function shouldGetPattern()
    {
        // when
        $pattern = pattern('\w');
        // then
        $this->assertSame('/\w/', $pattern->delimited());
    }

    /**
     * @test
     */
    public function shouldGetPatternDelimited()
    {
        // when
        $pattern = pattern('\w/\d');
        // then
        $this->assertSame('#\w/\d#', $pattern->delimited());
    }

    /**
     * @test
     */
    public function shouldGetPatternModifiers()
    {
        // when
        $pattern = pattern('\w', 'mi');
        // then
        $this->assertSame('/\w/mi', $pattern->delimited());
    }

    /**
     * @test
     */
    public function shouldGetPatternInstance()
    {
        // when
        $pattern = pattern('\w');
        // then
        $this->assertClassEquals($pattern, Pattern::class);
    }

    private function assertClassEquals(Pattern $pattern, string $expectedClassname): void
    {
        $this->assertSame($expectedClassname, \get_class($pattern));
    }

    /**
     * @test
     */
    public function shouldAcceptMalformedPattern()
    {
        // when
        pattern('+');
        // then
        $this->pass();
    }
}
