<?php
namespace Pheme\Stream;

class BinaryReaderTest extends \PHPUnit_Framework_TestCase
{

    public function test_empty()
    {
        $r = new ByteReader(new \EmptyIterator);
        $this->assertFalse($r->read());
        $this->assertFalse($r->read());
    }

    public function test_read()
    {
        $r = new ByteReader(new StringIterator('abc'));
        $this->assertEquals('a', $r->read());
        $this->assertEquals('b', $r->read());
        $this->assertEquals('c', $r->read());
        $this->assertFalse($r->read());
        $this->assertFalse($r->read());

        $r = new ByteReader(new FileIterator(__DIR__ . '/Fixtures/m.txt'));
        $this->assertEquals('a', $r->read());
        $this->assertEquals("\xE3", $r->read());
        $this->assertEquals("\x81", $r->read());
        $this->assertEquals("\x82", $r->read());
        $this->assertEquals('b', $r->read());
        $this->assertEquals("\r", $r->read());
        $this->assertEquals("\n", $r->read());
        $this->assertFalse($r->read());
        $this->assertFalse($r->read());
    }

    public function test_read_only_byte()
    {
        $this->setExpectedException(\UnexpectedValueException::class);
        $r = new ByteReader(\SplFixedArray::fromArray([1]));
        $r->read();
    }

}
