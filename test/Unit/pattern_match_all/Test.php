<?php
namespace Test\Unit\pattern_match_all;

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
    public function shouldMatchOne()
    {
        // when
        [$detail] = pattern_match_all('\w+', 'Winterfell');
        // then
        $this->assertSame('Winterfell', "$detail");
    }

    /**
     * @test
     */
    public function shouldMatchAll()
    {
        // when
        [$joffrey, $cersei, $ilyn, $hound] = pattern_match_all('\b[\w ]+', 'Joffrey, Cersei, Ilyn payne, The hound');
        // then
        $this->assertSame('Joffrey', "$joffrey");
        $this->assertSame('Cersei', "$cersei");
        $this->assertSame('Ilyn payne', "$ilyn");
        $this->assertSame('The hound', "$hound");
    }

    /**
     * @test
     */
    public function shouldMatchEmpty()
    {
        // when
        $matches = pattern_match_all('(*FAIL)', 'subject');
        // then
        $this->assertSame([], $matches);
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
        pattern_match_all('+', 'subject');
    }

    /**
     * @test
     */
    public function shouldFailForCatastrophicBacktracking()
    {
        // then
        $this->expectException(CatastrophicBacktrackingException::class);
        // when
        pattern_match_all($this->backtrackingPattern(), $this->backtrackingSubject(4));
    }

    /**
     * @test
     */
    public function shouldMatchSlash()
    {
        // when
        [$match] = pattern_match_all('/', '/');
        // then
        $this->assertSame('/', "$match");
    }

    /**
     * @test
     */
    public function shouldMatchControlCharacter()
    {
        // when
        [$match] = pattern_match_all('\c\\', \chr(28));
        // then
        $this->assertSame(\chr(28), "$match");
    }

    /**
     * @test
     */
    public function shouldMatchWithModifiers()
    {
        // when
        $matches = pattern_match_all('[A-Z]+', 'Family, Duty, Honor', 'i');
        // then
        $this->assertEquals(['Family', 'Duty', 'Honor'], $matches);
    }

    /**
     * @test
     */
    public function shouldGetOffsets()
    {
        // when
        [$fire, $and, $blood] = pattern_match_all('\w+', 'Fire and blood');
        // then
        $this->assertSame(0, $fire->offset());
        $this->assertSame(5, $and->offset());
        $this->assertSame(9, $blood->offset());
    }

    /**
     * @test
     */
    public function shouldGetGroups()
    {
        // when
        [$fire, $and, $blood] = pattern_match_all('(\w)\w+', 'Fire and blood');
        // then
        $this->assertSame('F', $fire->get(1));
        $this->assertSame('a', $and->get(1));
        $this->assertSame('b', $blood->get(1));
    }
}
