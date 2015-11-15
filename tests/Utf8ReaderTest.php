<?php
namespace Pheme\Stream;

class Utf8ReaderTest extends \PHPUnit_Framework_TestCase
{

    public function test_from()
    {
        $this->assertInstanceOf(Utf8Reader::class, Utf8Reader::from(new \EmptyIterator));
    }

    public function test_read()
    {
        $r = Utf8Reader::from(new FileIterator(__DIR__ . '/Fixtures/m.txt'));
        $this->assertEquals('a', $r->read());
        $this->assertEquals("あ", $r->read());
        $this->assertEquals('b', $r->read());
        $this->assertEquals("\r", $r->read());
        $this->assertEquals("\n", $r->read());
        $this->assertFalse($r->read());
        $this->assertFalse($r->read());
    }

    public function test_cannot_read_grapheme_cluster_sequence()
    {
        $r = Utf8Reader::from(new StringIterator("葛\xF3\xA0\x84\x81飾区"));
        $this->assertEquals("葛", $r->read());
        $this->assertEquals("\xF3\xA0\x84\x81", $r->read());
        $this->assertEquals("飾", $r->read());
        $this->assertEquals("区", $r->read());
        $this->assertFalse($r->read());
        $this->assertFalse($r->read());
    }

    public function test_throw_invalid_utf8()
    {
        $this->setExpectedException(\UnexpectedValueException::class);
        $r = Utf8Reader::from(new StringIterator("\xf0\x40\x82\xa0")); // HIRAGANA A in SJIS
        $r->read();
    }

    public function test_throw_invalid_ascii_in_utf8()
    {
        $this->setExpectedException(\UnexpectedValueException::class);
        $r = Utf8Reader::from(new StringIterator("\xc0\xbc")); // 0x3c '<'
        $r->read();
    }

}
