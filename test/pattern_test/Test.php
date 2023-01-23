<?php
namespace Test\pattern_test;

use PHPUnit\Framework\TestCase;
use TRegx\Exception\MalformedPatternException;

class Test extends TestCase
{
    /**
     * @test
     */
    public function shouldTest()
    {
        // when, then
        $this->assertTrue(pattern_test('\w+', 'word'));
    }

    /**
     * @test
     */
    public function shouldFail()
    {
        // when, then
        $this->assertFalse(pattern_test('\d+', 'word'));
    }

    /**
     * @test
     */
    public function shouldTestModifiers()
    {
        // when, then
        $this->assertTrue(pattern_test('word', 'WORD', 'i'));
    }

    /**
     * @test
     */
    public function shouldFailModifiers()
    {
        // when, then
        $this->assertFalse(pattern_test('word', 'before, word', 'A'));
    }

    /**
     * @test
     */
    public function shouldFailForMalformedPattern()
    {
        // then
        $this->expectException(MalformedPatternException::class);
        $this->expectExceptionMessage('Quantifier does not follow a repeatable item at offset 0');
        // when
        pattern_test('+', 'subject');
    }
}
