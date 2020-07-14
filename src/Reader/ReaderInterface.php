<?php

namespace App\Reader;

use Generator;

/**
 * Interface ReaderInterface
 */
interface ReaderInterface
{
    /**
     * @return Generator
     */
    public function getRecords():Generator;
}
