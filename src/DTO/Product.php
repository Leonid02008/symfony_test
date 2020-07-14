<?php

namespace App\DTO;

/**
 * Class SpecialPriceConstraint
 */
class Product
{
    /**
     * @var string
     */
    private $SKU;

    /**
     * @var string
     */
    private $description;

    /**
     * @var float
     */
    private $price;

    /**
     * @var float|null
     */
    private $specialPrice;

    /**
     * Product constructor.
     *
     * @param string $SKU
     * @param string $description
     * @param float $price
     * @param float|null $specialPrice
     */
    public function __construct(
        string $SKU = "",
        string $description = "",
        float $price = null,
        ?float $specialPrice = null
    ) {
        $this->SKU = (string)$SKU;
        $this->description = (string)$description;
        $this->price = $price;
        $this->specialPrice = $specialPrice;
    }

    /**
     * @return string
     */
    public function SKU(): string
    {
        return $this->SKU;
    }

    /**
     * @param string $SKU
     */
    public function setSKU(string $SKU): void
    {
        $this->SKU = $SKU;
    }

    /**
     * @return string
     */
    public function description(): string
    {
        return $this->description;
    }

    /**
     * @param string $description
     */
    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    /**
     * @return float
     */
    public function price(): float
    {
        return $this->price;
    }

    /**
     * @param float $price
     */
    public function setPrice(float $price): void
    {
        $this->price = $price;
    }

    /**
     * @return float
     */
    public function specialPrice(): ?float
    {
        return $this->specialPrice;
    }

    /**
     * @param float $specialPrice
     */
    public function setSpecialPrice(float $specialPrice): void
    {
        $this->specialPrice = $specialPrice;
    }
}
