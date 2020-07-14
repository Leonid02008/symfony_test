<?php

namespace App\Reader;

use Generator;

/**
 * Interface ReaderInterface
 */
interface FileReaderInterface
{
    /**
     * @param string $file
     */
    public function setFile(string $file): void;
}
