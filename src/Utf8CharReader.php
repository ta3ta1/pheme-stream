<?php
namespace Pheme\Stream;

class Utf8CharReader implements ReaderInterface
{

    /**
     * @var BufferedReaderInterface
     */
    private $reader;

    /**
     * @var int
     */
    private $maxCp;

    /**
     * @var bool
     */
    private $useIntl;

    /**
     * Utf8CharReader constructor.
     *
     * @param BufferedReaderInterface $reader
     * @param int $maxCp max number of code points in a character
     * @param bool|null $useIntl use intl extension
     */
    public function __construct(BufferedReaderInterface $reader, $maxCp, $useIntl = null)
    {
        $this->reader = $reader;
        $this->maxCp = $maxCp;
        if (extension_loaded('intl')) {
            if ($useIntl === null) {
                $this->useIntl = true;
            } else {
                $this->useIntl = boolval($useIntl);
            }
        } else {
            $this->useIntl = false;
        }
    }

    /**
     * @param \Iterator $source
     * @param int $maxCp max number of code points in a character
     * @param bool|null $useIntl use intl extension
     * @return static
     */
    public static function from(\Iterator $source, $maxCp = 10, $useIntl = null)
    {
        return new static(new BufferedReader(new Utf8Reader(new ByteReader($source)), $maxCp), $maxCp, $useIntl);
    }

    /**
     * @inheritDoc
     */
    public function read()
    {
        $c = $this->reader->read();
        if ($c === false) {
            return false;
        }

        // read grapheme clusters
        $try = $c;
        $numCp = 1;
        while ($numCp < $this->maxCp) {
            $next = $this->reader->read();
            if ($next === false) {
                break;
            }

            $try .= $next;
            if ($this->extract($try) === $try) {
                $c = $try;
                ++$numCp;
            } else {
                $this->reader->unread();
                break;
            }
        };

        return $c;
    }

    /**
     * @param $str
     * @return bool|string
     */
    private function extract($str)
    {
        if ($this->useIntl) {
            return grapheme_substr($str, 0, 1);
        } elseif (preg_match('/\X/u', $str, $m)) {
            return $m[0];
        } else {
            return false;
        }
    }
}
