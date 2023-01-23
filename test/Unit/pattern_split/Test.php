<?php
namespace Test\Unit\pattern_split;

use PHPUnit\Framework\TestCase;
use Test\Fixtures\CausesBacktracking;
use TRegx\Exception\MalformedPatternException;
use TRegx\SafeRegex\Exception\CatastrophicBacktrackingException;

class Test extends TestCase
{
    use CausesBacktracking;

    /**
     * @test
     */
    public function shouldSplit()
    {
        // when
        $split = pattern_split(' ', 'Winter is coming');
        // then
        $this->assertSame(['Winter', 'is', 'coming'], $split);
    }

    /**
     * @test
     */
    public function shouldSplitUnmatched()
    {
        // when
        $split = pattern_split('(*FAIL)', 'Winter is coming');
        // then
        $this->assertSame(['Winter is coming'], $split);
    }

    /**
     * @test
     */
    public function shouldSplitByPattern()
    {
        // when
        $split = pattern_split('\s', 'Winter is coming');
        // then
        $this->assertSame(['Winter', 'is', 'coming'], $split);
    }

    /**
     * @test
     */
    public function shouldSplitByPatternIncludeDelimiter()
    {
        // when
        $split = pattern_split('\s*(-)\s*', '12 -  13   -14-   15');
        // then
        $this->assertSame(['12', '-', '13', '-', '14', '-', '15'], $split);
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
        pattern_split('+', 'subject');
    }

    /**
     * @test
     */
    public function shouldThrowForCatastrophicBacktracking()
    {
        // then
        $this->expectException(CatastrophicBacktrackingException::class);
        // when
        pattern_split($this->backtrackingPattern(), $this->backtrackingSubject(0));
    }

    /**
     * @test
     */
    public function shouldSplitByControlCharacter()
    {
        // when
        $split = pattern_split('\c\\', 'a' . chr(28) . 'b');
        // then
        $this->assertSame(['a', 'b'], $split);
    }

    /**
     * @test
     */
    public function shouldSplitWithModifier()
    {
        // when
        $split = pattern_split('(o)', '--o--O--o--', 'i');
        // then
        $this->assertSame(['--', 'o', '--', 'O', '--', 'o', '--'], $split);
    }
}
