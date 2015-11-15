<?php
namespace Pheme\Stream;

class Utf8CharReaderTest extends \PHPUnit_Framework_TestCase
{

    public function test_from()
    {
        $this->assertInstanceOf(Utf8CharReader::class, Utf8CharReader::from(new \EmptyIterator));
    }

    public function test_read()
    {
        $r = Utf8CharReader::from(new StringIterator("a\xE3\x83\x9Db\xE3\x83\x9B\xE3\x82\x9Ac"), 0);
        $this->assertEquals('a', $r->read());
        $this->assertEquals("\xE3\x83\x9D", $r->read());
        $this->assertEquals('b', $r->read());
        $this->assertEquals("\xE3\x83\x9B", $r->read());
        $this->assertEquals("\xE3\x82\x9A", $r->read());
        $this->assertEquals('c', $r->read());
        $this->assertFalse($r->read());
        $this->assertFalse($r->read());

        $r = Utf8CharReader::from(new StringIterator("a\xE3\x83\x9Db\xE3\x83\x9B\xE3\x82\x9Ac"), 1);
        $this->assertEquals('a', $r->read());
        $this->assertEquals("\xE3\x83\x9D", $r->read());
        $this->assertEquals('b', $r->read());
        $this->assertEquals("\xE3\x83\x9B", $r->read());
        $this->assertEquals("\xE3\x82\x9A", $r->read());
        $this->assertEquals('c', $r->read());
        $this->assertFalse($r->read());
        $this->assertFalse($r->read());

        $r = Utf8CharReader::from(new StringIterator("a\xE3\x83\x9Db\xE3\x83\x9B\xE3\x82\x9Ac"), 2, false);
        $this->assertEquals('a', $r->read());
        $this->assertEquals("\xE3\x83\x9D", $r->read());
        $this->assertEquals('b', $r->read());
        $this->assertEquals("\xE3\x83\x9B\xE3\x82\x9A", $r->read());
        $this->assertEquals('c', $r->read());
        $this->assertFalse($r->read());
        $this->assertFalse($r->read());

        $r = Utf8CharReader::from(new StringIterator("a\xE3\x83\x9Db\xE3\x83\x9B\xE3\x82\x9Ac"), 2, true);
        $this->assertEquals('a', $r->read());
        $this->assertEquals("\xE3\x83\x9D", $r->read());
        $this->assertEquals('b', $r->read());
        $this->assertEquals("\xE3\x83\x9B\xE3\x82\x9A", $r->read());
        $this->assertEquals('c', $r->read());
        $this->assertFalse($r->read());
        $this->assertFalse($r->read());

        $r = Utf8CharReader::from(new StringIterator("\xE3\x83\x9B\xE3\x82\x9A\xE3\x82\x9A"), 3, false);
        $this->assertEquals("\xE3\x83\x9B\xE3\x82\x9A\xE3\x82\x9A", $r->read());
        $this->assertFalse($r->read());
        $this->assertFalse($r->read());

        $r = Utf8CharReader::from(new StringIterator("\xE3\x83\x9B\xE3\x82\x9A\xE3\x82\x9A"), 3, true);
        $this->assertEquals("\xE3\x83\x9B\xE3\x82\x9A\xE3\x82\x9A", $r->read());
        $this->assertFalse($r->read());
        $this->assertFalse($r->read());
    }

}
