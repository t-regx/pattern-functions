<?php
namespace Test\Unit\pattern_count;

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
    public function shouldCountZero()
    {
        // when
        $count = pattern_count('(*FAIL)', 'subject');
        // then
        $this->assertSame(0, $count);
    }

    /**
     * @test
     */
    public function shouldCountFirst()
    {
        // when
        $count = pattern_count('word', 'word');
        // then
        $this->assertSame(1, $count);
    }

    /**
     * @test
     */
    public function shouldCountMatchingSubject()
    {
        // when
        $count = pattern_count('\w+', 'word');
        // then
        $this->assertSame(1, $count);
    }

    /**
     * @test
     */
    public function shouldCountManyManyOccurrences()
    {
        // when
        $count = pattern_count('\w+', 'word, word, word');
        // then
        $this->assertSame(3, $count);
    }

    /**
     * @test
     */
    public function shouldAcceptString()
    {
        // when
        $count = pattern_count('1', true);
        // then
        $this->assertSame(1, $count);
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
        pattern_count('+', 'subject');
    }

    /**
     * @test
     */
    public function shouldFailForCatastrophicBacktracking()
    {
        // then
        $this->expectException(CatastrophicBacktrackingException::class);
        // when
        pattern_count($this->backtrackingPattern(), $this->backtrackingSubject(0));
    }

    /**
     * @test
     */
    public function shouldCountControlCharacter()
    {
        // when
        $count = pattern_count('\c\\', chr(28) . chr(28));
        // then
        $this->assertSame(2, $count);
    }

    /**
     * @test
     */
    public function shouldCountWithModifiers()
    {
        // when
        $count = pattern_count('[tnm]', 'Tell them the North remembers', 'i');
        // then
        $this->assertSame(8, $count);
    }
}
