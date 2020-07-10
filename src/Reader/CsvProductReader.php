<?php

namespace App\Reader;

use App\DTO\Product;
use Generator;

/**
 * Class CsvProductReader
 */
class CsvProductReader implements ReaderInterface
{
    /**
     * @var string
     */
    private $file;

    /**
     * {@inheritDoc}
     */
    public function setFile(string $file): void
    {
        $this->file = $file;
    }

    /**
     * {@inheritDoc}
     */
    public function getRecords(): Generator
    {
        $data = file_get_contents($this->file);
        $rows = explode("\n",$data);

        foreach($rows as $row) {
            $explodedRow = str_getcsv($row);
            yield new Product(...$explodedRow);
        }
    }
}
