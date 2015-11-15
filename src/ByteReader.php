<?php
namespace Pheme\Stream;

class ByteReader implements ReaderInterface
{

    /**
     * @var \Iterator
     */
    private $source;

    public function __construct(\Iterator $source)
    {
        $this->source = $source;
        $this->source->rewind();
    }

    /**
     * @inheritDoc
     * @throws \UnexpectedValueException
     */
    public function read()
    {
        if ($this->source->valid()) {
            $b = $this->source->current();

            // b should be byte
            if (!is_string($b) || strlen($b) != 1) {
                throw new \UnexpectedValueException('Need a byte');
            }

            $this->source->next();
            return $b;
        } else {
            return false;
        }
    }

}
