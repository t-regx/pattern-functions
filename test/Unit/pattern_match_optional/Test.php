<?php
namespace Test\Unit\pattern_match_optional;

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
    public function shouldMatchFirst()
    {
        // when
        $detail = pattern_match_optional('\w+', 'Chaos isn’t a pit. Chaos is a ladder.');
        // then
        $this->assertSame('Chaos', "$detail");
    }

    /**
     * @test
     */
    public function shouldThrowForMalformedPattern()
    {
        // then
        $this->expectException(MalformedPatternException::class);
        $this->expectExceptionMessage('Quantifier does not follow a repeatable item at offset 0');
        // when
        pattern_match_optional('+', 'subject');
    }

    /**
     * @test
     */
    public function shouldThrowForCatastrophicBacktracking()
    {
        // then
        $this->expectException(CatastrophicBacktrackingException::class);
        // when
        pattern_match_optional($this->backtrackingPattern(), $this->backtrackingSubject(0));
    }

    /**
     * @test
     */
    public function shouldNotExecuteUnnecessaryMatches()
    {
        // when
        pattern_match_optional($this->backtrackingPattern(), $this->backtrackingSubject(1));
        // then
        $this->pass();
    }

    /**
     * @test
     */
    public function shouldReturnNullForUnmatchedSubject()
    {
        // when
        $detail = pattern_match_optional('Nothing is true', 'Everything is permitted');
        // then
        $this->assertNull($detail);
    }

    /**
     * @test
     */
    public function shouldMatchControlCharacter()
    {
        // when
        $match = pattern_match_optional('\c\\', 'a' . chr(28) . 'b');
        // then
        $this->assertSame("\x1c", "$match");
    }

    /**
     * @test
     */
    public function shouldMatchSlash()
    {
        // when
        $match = pattern_match_optional('/', 'a/b');
        // then
        $this->assertSame('/', "$match");
    }

    /**
     * @test
     */
    public function shouldMatchWithModifiers()
    {
        // when
        $matched = pattern_match_optional('nIgHt', 'The night is dark and full of terrors', 'i');
        // then
        $this->assertSame('night', "$matched");
    }

    /**
     * @test
     */
    public function shouldFailForInvalidModifier()
    {
        // then
        $this->expectException(MalformedPatternException::class);
        $this->expectExceptionMessage("Unknown modifier 'f'");
        // when
        pattern_match_optional('foo', 'subject', 'f');
    }

    /**
     * @test
     */
    public function shouldMatchEmptyString()
    {
        // when
        $match = pattern_match_optional('', '');
        // then
        $this->assertSame('', "$match");
    }

    /**
     * @test
     */
    public function shouldGetOffset()
    {
        // when
        $detail = pattern_match_optional('word', '€€, word');
        // then
        $this->assertSame(4, $detail->offset());
        $this->assertSame(8, $detail->byteOffset());
    }

    /**
     * @test
     */
    public function shouldGetGroup()
    {
        // when
        $detail = pattern_match_optional('(M)ark', 'Mark');
        // then
        $this->assertSame('M', $detail->get(1));
    }
}
