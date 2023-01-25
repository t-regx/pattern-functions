<?php
namespace Test\Unit\pattern_match_first;

use PHPUnit\Framework\TestCase;
use Test\Fixtures\CausesBacktracking;
use Test\Fixtures\TestCasePasses;
use TRegx\CleanRegex\Exception\SubjectNotMatchedException;
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
        $detail = pattern_match_first('\w+', 'Chaos isn’t a pit. Chaos is a ladder.');
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
        pattern_match_first('+', 'subject');
    }

    /**
     * @test
     */
    public function shouldThrowForCatastrophicBacktracking()
    {
        // then
        $this->expectException(CatastrophicBacktrackingException::class);
        // when
        pattern_match_first($this->backtrackingPattern(), $this->backtrackingSubject(0));
    }

    /**
     * @test
     */
    public function shouldNotExecuteUnnecessaryMatches()
    {
        // when
        pattern_match_first($this->backtrackingPattern(), $this->backtrackingSubject(1));
        // then
        $this->pass();
    }

    /**
     * @test
     */
    public function shouldThrowForUnmatchedSubject()
    {
        // then
        $this->expectException(SubjectNotMatchedException::class);
        $this->expectExceptionMessage('Expected to get the first match, but subject was not matched');
        // when
        pattern_match_first('Nothing is true', 'Everything is permitted');
    }

    /**
     * @test
     */
    public function shouldMatchControlCharacter()
    {
        // when
        $match = pattern_match_first('\c\\', 'a' . chr(28) . 'b');
        // then
        $this->assertSame("\x1c", "$match");
    }

    /**
     * @test
     */
    public function shouldMatchSlash()
    {
        // when
        $match = pattern_match_first('/', 'a/b');
        // then
        $this->assertSame('/', "$match");
    }

    /**
     * @test
     */
    public function shouldMatchWithModifiers()
    {
        // when
        $matched = pattern_match_first('nIgHt', 'The night is dark and full of terrors', 'i');
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
        pattern_match_first('foo', 'subject', 'f');
    }

    /**
     * @test
     */
    public function shouldMatchEmptyString()
    {
        // when
        $match = pattern_match_first('', '');
        // then
        $this->assertSame('', "$match");
    }

    /**
     * @test
     */
    public function shouldGetOffset()
    {
        // when
        $detail = pattern_match_first('word', '€€, word');
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
        $detail = pattern_match_first('(M)ark', 'Mark');
        // then
        $this->assertSame('M', $detail->get(1));
    }
}
