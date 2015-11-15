<?php
namespace Pheme\Stream;

class BufferedReader implements BufferedReaderInterface
{

    /**
     * @var ReaderInterface
     */
    private $reader;

    /**
     * @var int
     */
    private $bufferLength;

    /**
     * @var array
     */
    private $buffer = [];

    /**
     * @var int
     */
    private $bufferIndex;

    /**
     * BufferedReader constructor.
     *
     * @param ReaderInterface $reader
     * @param int             $bufferCapacity
     */
    public function __construct(ReaderInterface $reader, $bufferCapacity)
    {
        $this->reader = $reader;
        $this->bufferLength = $bufferCapacity;
        if ($this->bufferLength < 0) {
            throw new \UnexpectedValueException("bufferCapacity must be >= 0");
        }
        $this->bufferIndex = 0;
    }

    /**
     * @inheritDoc
     */
    public function read()
    {
        if (!empty($this->buffer) && $this->bufferIndex < count($this->buffer)) {
            return $this->buffer[$this->bufferIndex++];
        } else {
            $read = $this->reader->read();
            if ($read !== false) {
                // update buffer
                array_push($this->buffer, $read);
                if (count($this->buffer) > $this->bufferLength) {
                    array_shift($this->buffer);
                }
                $this->bufferIndex = count($this->buffer);

                return $read;
            } else {
                return false;
            }
        }
    }

    /**
     * @inheritDoc
     */
    public function unread()
    {
        if ($this->bufferIndex == 0 || empty($this->buffer)) {
            throw new \LogicException("Cannot unread");
        } elseif ($this->bufferIndex <= count($this->buffer)) {
            --$this->bufferIndex;
        }
    }

}
