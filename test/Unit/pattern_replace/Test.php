<?php
namespace Test\Unit\pattern_replace;

use PHPUnit\Framework\TestCase;
use Test\Fixtures\CausesBacktracking;
use Test\Fixtures\TestCasePasses;
use TRegx\Exception\MalformedPatternException;
use TRegx\SafeRegex\Exception\CatastrophicBacktrackingException;

class Test extends TestCase
{
    use CausesBacktracking, TestCasePasses;

    /**
     * @test
     */
    public function shouldReplaceSpace()
    {
        // when
        $replaced = pattern_replace(' ', 'Valar Morghulis', '');
        // then
        $this->assertSame('ValarMorghulis', $replaced);
    }

    /**
     * @test
     */
    public function shouldReplaceMany()
    {
        // when
        $replaced = pattern_replace(' ', 'Winter is coming', '-');
        // then
        $this->assertSame('Winter-is-coming', $replaced);
    }

    /**
     * @test
     */
    public function shouldReplaceSpaceByRegularExpression()
    {
        // when
        $replaced = pattern_replace('\s', 'Winter is coming', '-');
        // then
        $this->assertSame('Winter-is-coming', $replaced);
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
        pattern_replace('+', 'subject', '-');
    }

    /**
     * @test
     */
    public function shouldFailForCatastrophicBacktracking()
    {
        // then
        $this->expectException(CatastrophicBacktrackingException::class);
        // when
        pattern_replace($this->backtrackingPattern(), $this->backtrackingSubject(3), 'replacement');
    }

    /**
     * @test
     */
    public function shouldReplaceControlCharacter()
    {
        // when
        $replaced = pattern_replace('\c\\', 'a' . chr(28) . 'b', '-');
        // then
        $this->assertSame('a-b', $replaced);
    }

    /**
     * @test
     */
    public function shouldReplaceSlash()
    {
        // when
        $replaced = pattern_replace('/', '/a/b/c', '-');
        // then
        $this->assertSame('-a-b-c', $replaced);
    }

    /**
     * @test
     */
    public function shouldIgnoreUnmatched()
    {
        // when
        pattern_replace('(*FAIL)', 'subject', 'replacement');
        // then
        $this->pass();
    }

    /**
     * @test
     */
    public function shouldReplaceWithModifiers()
    {
        // when
        $replaced = pattern_replace('WINTER', 'Winter is coming', 'Hangover', 'i');
        // then
        $this->assertSame('Hangover is coming', $replaced);
    }

    /**
     * @test
     */
    public function shouldReplaceReferenceLiterally()
    {
        // when
        $replaced = pattern_replace('foo(bar)', 'value:foobar', '$1');
        // then
        $this->assertSame('value:$1', $replaced);
    }

    /**
     * @test
     */
    public function shouldNotMistakeStringForCallable()
    {
        // when
        $replaced = pattern_replace('foo', 'foo', 'strToUpper');
        // then
        $this->assertSame('strToUpper', $replaced);
    }
}
