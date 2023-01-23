<?php
namespace Test\Unit\pattern_reject;

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
    public function shouldRejectFromArray()
    {
        // when
        $rejected = pattern_reject('[aeo]', [
            'mark',
            'jim',
            'john',
            'will'
        ]);
        // then
        $this->assertSame(['jim', 'will'], $rejected);
    }

    /**
     * @test
     */
    public function shouldRejectFromArrayPreserve()
    {
        // when
        $rejected = pattern_reject('(*FAIL)', [
            'mark',
            'jim',
            'john',
            'will'
        ]);
        // then
        $this->assertSame(['mark', 'jim', 'john', 'will'], $rejected);
    }

    /**
     * @test
     */
    public function shouldRejectArrayEmpty()
    {
        // when
        $rejected = pattern_reject('(*ACCEPT)', []);
        // then
        $this->assertSame([], $rejected);
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
        pattern_reject('+', ['one', 'two']);
    }

    /**
     * @test
     */
    public function shouldFailForMalformedPatternEmpty()
    {
        // then
        $this->expectException(MalformedPatternException::class);
        $this->expectExceptionMessage('Quantifier does not follow a repeatable item at offset 0');
        // when
        pattern_reject('?', []);
    }

    /**
     * @test
     */
    public function shouldFailForCatastrophicBacktracking()
    {
        // then
        $this->expectException(CatastrophicBacktrackingException::class);
        // when
        pattern_reject($this->backtrackingPattern(), [
            'foo',
            $this->backtrackingSubject(0)
        ]);
    }

    /**
     * @test
     */
    public function shouldRejectFromArrayModifierCaseInsensitive()
    {
        // when
        $rejected = pattern_reject('mark', [
            'mArK',
            'jim',
            'MARK',
            'will'
        ], 'i');
        // then
        $this->assertSame(['jim', 'will'], $rejected);
    }

    /**
     * @test
     */
    public function shouldRejectFromArrayModifierSingleLine()
    {
        // when
        $rejected = pattern_reject('a.b', [
            'a.b',
            "a\nb",
            'ab'
        ], 's');
        // then
        $this->assertSame(['ab'], $rejected);
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
        pattern_reject('foo', [], 'f');
    }
}
