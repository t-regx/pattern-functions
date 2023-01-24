<?php
namespace Test\Unit\pattern_replace_callback;

use PHPUnit\Framework\TestCase;
use Test\Fixtures\Functions;
use TRegx\CleanRegex\Exception\InvalidReplacementException;
use TRegx\CleanRegex\Match\Detail;
use TRegx\Exception\MalformedPatternException;

class Test extends TestCase
{
    /**
     * @test
     */
    public function shouldReplace()
    {
        // given
        $subject = 'Most men would rather deny a hard truth than face it';
        // when
        $replaced = pattern_replace_callback(' ', $subject, Functions::constant('.'));
        // then
        $this->assertSame('Most.men.would.rather.deny.a.hard.truth.than.face.it', $replaced);
    }

    /**
     * @test
     */
    public function shouldReplaceCallback()
    {
        // given
        $subject = 'Most men would rather deny a hard truth than face it';
        // when
        $replaced = pattern_replace_callback('\b\w{4,5}\b', $subject, Functions::toUpper());
        // then
        $this->assertSame('MOST men WOULD rather DENY a HARD TRUTH THAN FACE it', $replaced);
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
        pattern_replace_callback('+', 'subject', Functions::throws());
    }

    /**
     * @test
     */
    public function shouldFailForInvalidReturnType()
    {
        // then
        $this->expectException(InvalidReplacementException::class);
        $this->expectExceptionMessage('Invalid callback() callback return type. Expected string, but boolean (false) given');
        // when
        pattern_replace_callback('subject', 'subject', Functions::constant(false));
    }

    /**
     * @test
     */
    public function shouldReplaceControlCharacter()
    {
        // when
        $replaced = pattern_replace_callback('\c\\', 'a' . chr(28) . 'b', Functions::constant('-'));
        // then
        $this->assertSame('a-b', $replaced);
    }

    /**
     * @test
     */
    public function shouldReplaceSlash()
    {
        // when
        $replaced = pattern_replace_callback('/', 'a/b/c', Functions::constant('-'));
        // then
        $this->assertSame('a-b-c', $replaced);
    }

    /**
     * @test
     */
    public function shouldGetOffset()
    {
        // when
        pattern_replace_callback('\w+', '...word', Functions::out($detail, ''));
        // then
        $this->assertSame(3, $detail->offset());
    }

    /**
     * @test
     */
    public function shouldCallWithDetail()
    {
        // when, then
        pattern_replace_callback('\w+', '...word', Functions::assertInstanceOf(Detail::class, ''));
    }

    /**
     * @test
     */
    public function shouldReplaceWithModifier()
    {
        // when
        $subject = 'word,Word,wOrD';
        $replaced = pattern_replace_callback('WORD', $subject, Functions::constant('new'), 'i');
        // then
        $this->assertSame('new,new,new', $replaced);
    }
}
