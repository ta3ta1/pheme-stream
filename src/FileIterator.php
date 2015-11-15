<?php
namespace Pheme\Stream;

class FileIterator implements \Iterator
{
    /**
     * @var \SplFileObject
     */
    private $file;

    /**
     * @var string
     */
    private $buffer;

    /**
     * @var int
     */
    private $index;

    /**
     * FileIterator constructor.
     *
     * @param string $path
     */
    public function __construct($path)
    {
        $this->file = new \SplFileObject($path, 'rb');
    }

    /**
     * @inheritDoc
     */
    public function current()
    {
        return $this->buffer;
    }

    /**
     * @inheritDoc
     */
    public function next()
    {
        $this->buffer = $this->file->fgetc();
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
        return $this->buffer !== false;
    }

    /**
     * @inheritDoc
     */
    public function rewind()
    {
        $this->file->fseek(0);
        $this->index = -1;
        $this->next();
    }
}
