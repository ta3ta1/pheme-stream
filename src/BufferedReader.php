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
    private $bufferCapacity;

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
        $this->bufferCapacity = $bufferCapacity;
        if ($this->bufferCapacity < 0) {
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
                $this->updateBuffer($read);

                return $read;
            } else {
                // no duplicated eof.
                $last = empty($this->buffer) ? null : $this->buffer[count($this->buffer) - 1];
                if ($last !== false) {
                    $this->updateBuffer($read);
                }

                return false;
            }
        }
    }

    private function updateBuffer($value)
    {
        array_push($this->buffer, $value);
        if (count($this->buffer) > $this->bufferCapacity) {
            array_shift($this->buffer);
        }
        $this->bufferIndex = count($this->buffer);
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
