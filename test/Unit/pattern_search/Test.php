<?php
namespace Test\Unit\pattern_search;

use PHPUnit\Framework\TestCase;
use TRegx\Exception\MalformedPatternException;

class Test extends TestCase
{
    /**
     * @test
     */
    public function shouldMatchAll()
    {
        // when
        [$ours, $is, $the, $fury] = pattern_search('\w+', 'Ours is the Fury');
        // then
        $this->assertSame('Ours', "$ours");
        $this->assertSame('is', "$is");
        $this->assertSame('the', "$the");
        $this->assertSame('Fury', "$fury");
    }

    /**
     * @test
     */
    public function shouldMatchWithModifiers()
    {
        // when
        $matches = pattern_search('word', 'wOrD', 'i');
        // then
        $this->assertSame(['wOrD'], $matches);
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
        pattern_search('?', 'subject');
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
        pattern_search('pattern', 'subject', 'f');
    }
}
