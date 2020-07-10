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

class ProductImportService implements ImportServiceInterface
{
    const RECORD_CREATED = "created";
    const RECORD_UPDATED = "updated";
    /**
     * @var array
     */
    private $createdSKUs;

    /**
     * @var array
     */
    private $updatedSKUs;

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

        $this->createdSKUs = [];
        $this->updatedSKUs = [];
        $this->errorCount = 0;

        /** @var DTOProduct $record */
        foreach ($reader->getRecords() as $record) {
            $this->processRecord($record);
        }

        $this->logger->info(
            "import was successful\n" .
            "created records: " . count($this->createdSKUs) . "\n" .
            "updated records: " . count($this->updatedSKUs) . "\n" .
            "errors in records: " . $this->errorCount . "\n"
        );

        return new ImportResults($this->errorCount,count($this->createdSKUs),count($this->updatedSKUs));
    }

    private function processRecord(DTOProduct $record): void
    {
        if($product = $this->productRepository->find($record->SKU())) {
            $this->updateRecord($product, $record);
            $type = self::RECORD_UPDATED;
        } else {
            $product = $this->productFactory->create($record);
            $type = self::RECORD_CREATED;
        }

        $errors = $this->validator->validate($product);

        if(count($errors) > 0) {
            $this->printErrors($errors, $product);
            $this->errorCount++;
            return;
        }

        $this->productRepository->save($product);

        $this->fillResponseData($product, $type);

    }

    private function fillResponseData(Product $product, string $type): void
    {
        if(in_array($product->SKU(), $this->createdSKUs) || in_array($product->SKU(), $this->updatedSKUs)){
            return;
        }

        if($type === self::RECORD_CREATED){
            $this->createdSKUs[] = $product->SKU();
            return;
        }
        if($type === self::RECORD_UPDATED){
            $this->updatedSKUs[] = $product->SKU();
            return;
        }
    }

    private function updateRecord(Product &$product, DTOProduct $record): void
    {
        $product->setDescription($record->description());
        $product->setPrice($record->price());
        $product->setSpecialPrice($record->specialPrice());
    }

    /**
     * @param ConstraintViolationListInterface $errors
     */
    private function printErrors(ConstraintViolationListInterface $errors, Product $product): void
    {
        /** @var ConstraintViolation $error */
        foreach ($errors as $error) {
            $this->logger->warning( "Product SKU: " . $product->SKU() . " message: " .  $error->getMessage());
        }
    }
}