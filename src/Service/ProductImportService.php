<?php

namespace App\Service;

use App\DTO\ImportResults;
use App\DTO\Product as DTOProduct;
use App\Entity\Product;
use App\Factory\ProductFactoryInterface;
use App\Reader\ReaderInterface;
use App\Repository\ProductRepositoryInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * Class ProductImportService
 *
 * @package App\Service
 */
class ProductImportService implements ImportServiceInterface
{
    public const RECORD_CREATED = "created";
    public const RECORD_UPDATED = "updated";
    public const RECORD_ERROR = "error";

    /**
     * @var int
     */
    private $createdCount;

    /**
     * @var int
     */
    private $updatedCount;

    /**
     * @var int
     */
    private $errorCount;

    /**
     * @var ValidatorInterface
     */
    private $validator;

    /**
     * @var ProductRepositoryInterface
     */
    private $productRepository;

    /**
     * @var ProductFactoryInterface
     */
    private $productFactory;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * ProductImportService constructor.
     *
     * @param ValidatorInterface $validator
     * @param ProductRepositoryInterface $productRepository
     * @param ProductFactoryInterface $productFactory
     * @param LoggerInterface $logger
     */
    public function __construct(
        ValidatorInterface $validator,
        ProductRepositoryInterface $productRepository,
        ProductFactoryInterface $productFactory,
        LoggerInterface $logger
    ) {
        $this->validator = $validator;
        $this->productRepository = $productRepository;
        $this->productFactory = $productFactory;
        $this->logger = $logger;
    }

    /**
     * {@inheritDoc}
     */
    public function execute(ReaderInterface $reader): ImportResults
    {
        $this->errorCount = 0;
        $this->createdCount = 0;
        $this->updatedCount = 0;

        $iterator = 0;
        /** @var DTOProduct $record */
        foreach ($reader->getRecords() as $record) {
            $this->processRecord($record);
            ++$iterator;
            if ($iterator % 500 === 0) {
                $this->productRepository->flush();
            }
        }
        if ($iterator % 500 !== 0) {
            $this->productRepository->flush();
        }

        $this->logger->info(
            "import was successful\n" .
            "created records: " . $this->createdCount . "\n" .
            "updated records: " . $this->updatedCount . "\n" .
            "errors in records: " . $this->errorCount . "\n"
        );

        return new ImportResults($this->errorCount, $this->createdCount, $this->updatedCount);
    }

    /**
     * @param DTOProduct $record
     *
     * @throws \Exception
     */
    private function processRecord(DTOProduct $record): void
    {
        if ($product = $this->productRepository->find($record->SKU())) {
            $this->updateRecord($product, $record);
            $type = self::RECORD_UPDATED;
        } else {
            $product = $this->productFactory->create($record);
            $type = self::RECORD_CREATED;
        }

        $errors = $this->validator->validate($product);

        if (count($errors) > 0) {
            $this->printErrors($errors, $product);
            $this->errorCount++;
            return;
        }

        $this->productRepository->persist($product);
        $this->fillResponseData($type);
    }

    /**
     *  @param string $type
     */
    private function fillResponseData(string $type): void
    {
        if ($type === self::RECORD_CREATED) {
            $this->createdCount++;
            return;
        }
        if ($type === self::RECORD_UPDATED) {
            $this->updatedCount++;
            return;
        }
    }

    /**
     * @param Product $product
     *
     * @param DTOProduct $record
     */
    private function updateRecord(Product &$product, DTOProduct $record): void
    {
        $product->setDescription($record->description());
        $product->setPrice($record->price());
        $product->setSpecialPrice($record->specialPrice());
    }

    /**
     * @param ConstraintViolationListInterface $errors
     * @param Product $product
     */
    private function printErrors(ConstraintViolationListInterface $errors, Product $product): void
    {
        /** @var ConstraintViolation $error */
        foreach ($errors as $error) {
            $this->logger->warning("Product SKU: " . $product->SKU() . " message: " .  $error->getMessage());
        }
    }
}
