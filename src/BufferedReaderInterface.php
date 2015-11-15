<?php
namespace Pheme\Stream;

interface BufferedReaderInterface extends ReaderInterface
{

    /**
     * Go one step back on the stream.
     * Next read() returns last same value.
     *
     * @return void
     */
    public function unread();

}
