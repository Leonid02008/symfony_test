<?php

namespace App\Reader;

use Generator;

/**
 * Interface ReaderInterface
 */
interface ReaderInterface
{
    /**
     * @param string $file
     */
    public function setFile(string $file):void;

    /**
     * @return Generator
     */
    public function getRecords():Generator;
}
