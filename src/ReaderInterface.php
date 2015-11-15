<?php
namespace Pheme\Stream;

interface ReaderInterface
{

    /**
     * Read a value from stream and go one step forward on the stream.
     *
     * @return mixed read value, false when end of stream
     */
    public function read();

}
