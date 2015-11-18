<?php
namespace Pheme\Stream;

class BufferedReaderTest extends \PHPUnit_Framework_TestCase
{

    public function test_empty()
    {
        $r = new BufferedReader(new ByteReader(new \EmptyIterator), 0);
        $this->assertFalse($r->read());
        $this->assertFalse($r->read());
    }

    public function test_read()
    {
        $r = new BufferedReader(new ByteReader(new StringIterator('abc')), 0);
        $this->assertEquals('a', $r->read());
        $this->assertEquals('b', $r->read());
        $this->assertEquals('c', $r->read());
        $this->assertFalse($r->read());
        $this->assertFalse($r->read());

        $r = new BufferedReader(new ByteReader(new StringIterator('abc')), 2);
        $this->assertEquals('a', $r->read());
        $this->assertEquals('b', $r->read());
        $this->assertEquals('c', $r->read());
        $r->unread();
        $r->unread();
        $this->assertEquals('b', $r->read());
        $this->assertEquals('c', $r->read());
        $this->assertFalse($r->read());
        $this->assertFalse($r->read());
        $r->unread();
        $r->unread();
        $this->assertEquals('c', $r->read());
        $this->assertFalse($r->read());
        $this->assertFalse($r->read());

        $r = new BufferedReader(Utf8Reader::from(new FileIterator(__DIR__ . '/Fixtures/m.txt')), 1);
        $this->assertEquals('a', $r->read());
        $this->assertEquals("あ", $r->read());
        $r->unread();
        $this->assertEquals("あ", $r->read());
        $this->assertEquals('b', $r->read());
        $this->assertEquals("\r", $r->read());
        $this->assertEquals("\n", $r->read());
        $this->assertFalse($r->read());
        $r->unread();
        $this->assertFalse($r->read());
        $this->assertFalse($r->read());
    }

    public function test_unread_throws_no_read()
    {
        $this->setExpectedException(\LogicException::class);
        $r = new BufferedReader(new ByteReader(new StringIterator('abc')), 5);
        $r->unread();
    }

    public function test_unread_throws_too_many_unread()
    {
        $this->setExpectedException(\LogicException::class);
        $r = new BufferedReader(new ByteReader(new StringIterator('abc')), 5);
        $r->read();
        $r->unread();
        $r->unread();
    }

}
