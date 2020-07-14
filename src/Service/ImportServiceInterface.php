<?php

namespace App\Service;

use App\DTO\ImportResults;
use App\Reader\ReaderInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

/**
 * Interface ImportInterface
 */
interface ImportServiceInterface
{
    /**
     * @param ReaderInterface $reader
     *
     * @return ImportResults
     */
    public function execute(ReaderInterface $reader): ImportResults;
}
