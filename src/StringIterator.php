<?php
namespace Pheme\Stream;

class StringIterator implements \Iterator
{

    /**
     * @var string
     */
    private $source;

    /**
     * @var int
     */
    private $length;

    /**
     * @var int
     */
    private $index;

    /**
     * StringIterator constructor.
     *
     * @param string $source
     */
    public function __construct($source)
    {
        $this->source = $source;
        $this->length = strlen($source);
        $this->index = 0;
    }

    /**
     * @inheritDoc
     */
    public function current()
    {
        return $this->source[$this->index];
    }

    /**
     * @inheritDoc
     */
    public function next()
    {
        ++$this->index;
    }

    /**
     * @inheritDoc
     */
    public function key()
    {
        return $this->index;
    }

    /**
     * @inheritDoc
     */
    public function valid()
    {
        return $this->index < $this->length;
    }

    /**
     * @inheritDoc
     */
    public function rewind()
    {
        $this->index = 0;
    }
}
