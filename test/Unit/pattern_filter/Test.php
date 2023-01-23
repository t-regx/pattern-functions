<?php
namespace Test\Unit\pattern_filter;

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
    public function shouldFilterArray()
    {
        // when
        $filtered = pattern_filter('[aeo]', [
            'mark',
            'jim',
            'john',
            'will'
        ]);
        // then
        $this->assertSame(['mark', 'john'], $filtered);
    }

    /**
     * @test
     */
    public function shouldFilterArrayReject()
    {
        // when
        $filtered = pattern_filter('(*FAIL)', [
            'mark',
            'jim',
            'john',
            'will'
        ]);
        // then
        $this->assertSame([], $filtered);
    }

    /**
     * @test
     */
    public function shouldFilterArrayEmpty()
    {
        // when
        $filtered = pattern_filter('(*ACCEPT)', []);
        // then
        $this->assertSame([], $filtered);
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
        pattern_filter('+', ['one', 'two']);
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
        pattern_filter('?', []);
    }

    /**
     * @test
     */
    public function shouldFailForCatastrophicBacktracking()
    {
        // then
        $this->expectException(CatastrophicBacktrackingException::class);
        // when
        pattern_filter($this->backtrackingPattern(), [
            'foo',
            $this->backtrackingSubject(0)
        ]);
    }

    /**
     * @test
     */
    public function shouldFilterArrayModifierCaseInsensitive()
    {
        // when
        $filtered = pattern_filter('mark', [
            'mArK',
            'jim',
            'MARK',
            'will'
        ], 'i');
        // then
        $this->assertSame(['mArK', 'MARK'], $filtered);
    }

    /**
     * @test
     */
    public function shouldFilterArrayModifierSingleLine()
    {
        // when
        $filtered = pattern_filter('a.b', [
            'a.b',
            "a\nb"
        ], 's');
        // then
        $this->assertSame(['a.b', "a\nb"], $filtered);
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
        pattern_filter('foo', [], 'f');
    }
}
