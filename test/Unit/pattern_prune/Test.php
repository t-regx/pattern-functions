<?php
namespace Test\Unit\pattern_prune;

use PHPUnit\Framework\TestCase;
use Test\Fixtures\CausesBacktracking;
use TRegx\CleanRegex\Pattern;
use TRegx\Exception\MalformedPatternException;
use TRegx\SafeRegex\Exception\CatastrophicBacktrackingException;

class Test extends TestCase
{
    use CausesBacktracking;

    /**
     * @test
     */
    public function shouldPruneSubject()
    {
        // when
        $pruned = pattern_prune(' ', 'Winter is coming');
        // then
        $this->assertSame('Winteriscoming', $pruned);
    }

    /**
     * @test
     */
    public function shouldPruneUnmatched()
    {
        // when
        $pruned = pattern_prune('(*FAIL)', 'Winter is coming');
        // then
        $this->assertSame('Winter is coming', $pruned);
    }

    /**
     * @test
     */
    public function shouldPruneSubjectRegularExpression()
    {
        // when
        $pruned = pattern_prune('\s?than|swords\s?', 'Fear cuts deeper than swords');
        // then
        $this->assertSame('Fear cuts deeper ', $pruned);
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
        pattern_prune('+', 'subject');
    }

    /**
     * @test
     */
    public function shouldFailForCatastrophicBacktracking()
    {
        // then
        $this->expectException(CatastrophicBacktrackingException::class);
        // when
        pattern_prune($this->backtrackingPattern(), $this->backtrackingSubject(3));
    }

    /**
     * @test
     */
    public function shouldPruneSlash()
    {
        // when
        $pruned = pattern_prune('/', 'a/b/c');
        // then
        $this->assertSame('abc', $pruned);
    }

    /**
     * @test
     */
    public function shouldPruneWithModifiers()
    {
        // when
        $pruned = pattern_prune('B', 'a/b/c', Pattern::CASE_INSENSITIVE);
        // then
        $this->assertSame('a//c', $pruned);
    }

    /**
     * @test
     */
    public function shouldFailForInvalidModifier()
    {
        // then
        $this->expectException(MalformedPatternException::class);
        $this->expectExceptionMessage('Unrecognized character follows \ at offset 1');
        // when
        pattern_prune('\O', 'subject', Pattern::RESTRICTIVE_ESCAPE);
    }
}
