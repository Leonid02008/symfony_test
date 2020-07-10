<?php

namespace App\Reader;

use App\DTO\Product as DTOProduct;
use App\Factory\ProductFactoryInterface;
use Generator;
use Psr\Log\LoggerInterface;
use Symfony\Component\ErrorHandler\Error\FatalError;

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
     * @var LoggerInterface
     */
    private $logger;

    /**
     * CsvProductReader constructor.
     *
     * @param LoggerInterface $logger
     */
    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

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

        foreach($rows as $index => $row) {
            try {
                $explodedRow = str_getcsv($row);
                $product = new DTOProduct(...$explodedRow);
            } catch (\Error $exception) {
                $this->logger->warning("Error during product reading. Row index: " . $index);
                continue;
            }
            yield $product;

        }
    }
}
