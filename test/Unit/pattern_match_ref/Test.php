<?php
namespace Test\Unit\pattern_match_ref;

use PHPUnit\Framework\TestCase;
use Test\Fixtures\CausesBacktracking;
use Test\Fixtures\TestCasePasses;
use TRegx\Exception\MalformedPatternException;
use TRegx\SafeRegex\Exception\CatastrophicBacktrackingException;
use function pattern_match_ref;

class Test extends TestCase
{
    use CausesBacktracking, TestCasePasses;

    /**
     * @test
     */
    public function shouldMatchFirst()
    {
        // when
        pattern_match_ref('\w+', 'Chaos isn’t a pit. Chaos is a ladder.', $detail);
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
        pattern_match_ref('+', 'subject', $detail);
    }

    /**
     * @test
     */
    public function shouldThrowForCatastrophicBacktracking()
    {
        // then
        $this->expectException(CatastrophicBacktrackingException::class);
        // when
        pattern_match_ref($this->backtrackingPattern(), $this->backtrackingSubject(0), $detail);
    }

    /**
     * @test
     */
    public function shouldNotExecuteUnnecessaryMatches()
    {
        // when
        pattern_match_ref($this->backtrackingPattern(), $this->backtrackingSubject(1), $detail);
        // then
        $this->pass();
    }

    /**
     * @test
     */
    public function shouldReturnNullForUnmatchedSubject()
    {
        // when
        pattern_match_ref('Nothing is true', 'Everything is permitted', $detail);
        // then
        $this->assertNull($detail);
    }

    /**
     * @test
     */
    public function shouldMatchControlCharacter()
    {
        // when
        pattern_match_ref('\c\\', 'a' . chr(28) . 'b', $detail);
        // then
        $this->assertSame("\x1c", "$detail");
    }

    /**
     * @test
     */
    public function shouldMatchSlash()
    {
        // when
        pattern_match_ref('/', 'a/b', $detail);
        // then
        $this->assertSame('/', "$detail");
    }

    /**
     * @test
     */
    public function shouldMatchWithModifiers()
    {
        // when
        pattern_match_ref('nIgHt', 'The night is dark and full of terrors', $detail, 'i');
        // then
        $this->assertSame('night', "$detail");
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
        pattern_match_ref('foo', 'subject', $detail, 'f');
    }

    /**
     * @test
     */
    public function shouldMatchEmptyString()
    {
        // when
        pattern_match_ref('', '', $detail);
        // then
        $this->assertSame('', "$detail");
    }

    /**
     * @test
     */
    public function shouldGetOffset()
    {
        // when
        pattern_match_ref('word', '€€, word', $detail);
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
        pattern_match_ref('(M)ark', 'Mark', $detail);
        // then
        $this->assertSame('M', $detail->get(1));
    }

    /**
     * @test
     */
    public function shouldOverrideDetail()
    {
        // given
        $detail = pattern_match_first('previous', 'previous');
        // when
        pattern_match_ref('(*FAIL)', 'subject', $detail);
        // then
        $this->assertNull($detail);
    }

    /**
     * @test
     */
    public function shouldReturnTrueForMatchedSubject()
    {
        // when
        $result = pattern_match_ref('Mark', 'Mark', $detail);
        // then
        $this->assertTrue($result);
    }

    /**
     * @test
     */
    public function shouldReturnFalseForUnmatchedSubject()
    {
        // when
        $result = pattern_match_ref('(*FAIL)', 'John', $detail);
        // then
        $this->assertFalse($result);
    }
}
