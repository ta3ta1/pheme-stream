<?php
namespace Pheme\Stream;

class FileIteratorTest extends \PHPUnit_Framework_TestCase
{

    public function test_iteration()
    {
        $this->assertEquals(
            [],
            iterator_to_array(new FileIterator(__DIR__ . '/Fixtures/empty.txt')));
        $this->assertEquals(
            ['a', 'b', "\r", "\n", 'c', "\r", "\n"],
            iterator_to_array(new FileIterator(__DIR__ . '/Fixtures/a.txt')));
        $this->assertEquals(
            ['a', "\xE3", "\x81", "\x82", 'b', "\r", "\n"],
            iterator_to_array(new FileIterator(__DIR__ . '/Fixtures/m.txt')));
    }

}
