<?php
namespace Pheme\Stream;

class Utf8Reader implements ReaderInterface
{

    /**
     * @var ReaderInterface
     */
    private $reader;

    /**
     * Utf8Reader constructor.
     *
     * @param ReaderInterface $reader
     */
    public function __construct(ReaderInterface $reader)
    {
        $this->reader = $reader;
    }

    /**
     * @param \Iterator $source
     * @return static
     */
    public static function from(\Iterator $source)
    {
        return new static(new ByteReader($source));
    }

    /**
     * @inheritDoc
     * @throws \UnexpectedValueException
     */
    public function read()
    {
        // first byte
        $c = $this->reader->read();
        if ($c === false) {
            return false;
        }

        // read until valid utf8
        while (!preg_match('//u', $c) && strlen($c) <= 4) {
            $next = $this->reader->read();
            if ($next !== false) {
                $c .= $next;
            } else {
                break;
            }
        }

        if (!preg_match('//u', $c)) {
            throw new \UnexpectedValueException('Invalid utf8: 0x' . bin2hex($c));
        }

        return $c;
    }

}
