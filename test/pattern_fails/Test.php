<?php
namespace Test\pattern_fails;

use PHPUnit\Framework\TestCase;
use TRegx\Exception\MalformedPatternException;
use function pattern_fails;

class Test extends TestCase
{
    /**
     * @test
     */
    public function shouldFail()
    {
        // when, then
        $this->assertTrue(pattern_fails('\d+', 'word', ''));
    }

    /**
     * @test
     */
    public function shouldNotFail()
    {
        // when, then
        $this->assertFalse(pattern_fails('\w+', 'word', ''));
    }

    /**
     * @test
     */
    public function shouldFailModifiers()
    {
        // when, then
        $this->assertTrue(pattern_fails('word', 'before, word', 'A'));
    }

    /**
     * @test
     */
    public function shouldNotFailModifiers()
    {
        // when, then
        $this->assertFalse(pattern_fails('word', 'WORD', 'i'));
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
        pattern_fails('+', 'subject', '');
    }
}
