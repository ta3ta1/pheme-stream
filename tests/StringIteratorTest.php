<?php
namespace Pheme\Stream;

class StringIteratorTest extends \PHPUnit_Framework_TestCase
{

    public function test_iteration()
    {
        $this->assertEquals([], iterator_to_array(new StringIterator('')));
        $this->assertEquals(['a'], iterator_to_array(new StringIterator('a')));
        $this->assertEquals(['a', 'b'], iterator_to_array(new StringIterator('ab')));
        $this->assertEquals(
            ['a', "\xE3", "\x81", "\x82", 'b'],
            iterator_to_array(new StringIterator("aあb")));
    }

}
