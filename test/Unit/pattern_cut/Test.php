<?php
namespace Test\Unit\pattern_cut;

use PHPUnit\Framework\TestCase;
use Test\Fixtures\CausesBacktracking;
use Test\Fixtures\TestCasePasses;
use TRegx\CleanRegex\Exception\UnevenCutException;
use TRegx\Exception\MalformedPatternException;
use TRegx\SafeRegex\Exception\CatastrophicBacktrackingException;

class Test extends TestCase
{
    use CausesBacktracking, TestCasePasses;

    /**
     * @test
     */
    public function shouldSplit()
    {
        // when
        $pieces = pattern_cut(' ', 'Valar morghulis');
        // then
        $this->assertSame(['Valar', 'morghulis'], $pieces);
    }

    /**
     * @test
     */
    public function shouldCatByPattern()
    {
        // when
        $pieces = pattern_cut('\s', 'Valar morghulis');
        // then
        $this->assertSame(['Valar', 'morghulis'], $pieces);
    }

    /**
     * @test
     */
    public function shouldNotIncludeDelimiter()
    {
        // when
        $pieces = pattern_cut('1(2)3', '<123>');
        // then
        $this->assertSame(['<', '>'], $pieces);
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
        pattern_cut('+', 'subject');
    }

    /**
     * @test
     */
    public function shouldThrowForCatastrophicBacktracking()
    {
        // then
        $this->expectException(CatastrophicBacktrackingException::class);
        // when
        pattern_cut($this->backtrackingPattern(), $this->backtrackingSubject(0));
    }

    /**
     * @test
     */
    public function shouldCutByControlCharacter()
    {
        // when
        $pieces = pattern_cut('\c\\', 'a' . chr(28) . 'b');
        // then
        $this->assertSame(['a', 'b'], $pieces);
    }

    /**
     * @test
     */
    public function shouldCutWithModifier()
    {
        // when
        $split = pattern_cut('(o)', '--O--', 'i');
        // then
        $this->assertSame(['--', '--'], $split);
    }

    /**
     * @test
     */
    public function shouldThrowForUnevenCutOverflowing()
    {
        // then
        $this->expectException(UnevenCutException::class);
        $this->expectExceptionMessage('Expected the pattern to make exactly 1 cut, but 2 or more cuts were matched');
        // when
        pattern_cut('word', 'word, word, word');
    }

    /**
     * @test
     */
    public function shouldThrowForUnevenCutUnderflowing()
    {
        // then
        $this->expectException(UnevenCutException::class);
        $this->expectExceptionMessage("Expected the pattern to make exactly 1 cut, but the pattern doesn't match the subject");
        // when
        pattern_cut('(*FAIL)', 'Winter is coming');
    }

    /**
     * @test
     */
    public function shouldNotExecuteUnnecessaryMatches()
    {
        // then
        $this->expectException(UnevenCutException::class);
        // when
        pattern_cut($this->backtrackingPattern(), $this->backtrackingSubject(2));
    }

    /**
     * @test
     */
    public function shouldCutEmpty()
    {
        // when
        $split = pattern_cut('-', '-');
        // then
        $this->assertSame(['', ''], $split);
    }

    /**
     * @test
     */
    public function shouldCutEmptyStart()
    {
        // when
        $split = pattern_cut('-', '-Foo');
        // then
        $this->assertSame(['', 'Foo'], $split);
    }

    /**
     * @test
     */
    public function shouldCutEmptyEnd()
    {
        // when
        $split = pattern_cut('-', 'Foo-');
        // then
        $this->assertSame(['Foo', ''], $split);
    }
}
