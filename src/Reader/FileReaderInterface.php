<?php

namespace App\Reader;

/**
 * Interface FileReaderInterface
 *
 * @package App\Reader
 */
interface FileReaderInterface
{
    /**
     * @param string $file
     */
    public function setFile(string $file): void;
}
