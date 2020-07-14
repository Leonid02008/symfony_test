<?php

namespace App\Entity;

use App\Repository\ProductRepositoryInterface;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints;
use App\Validator\Constraint\SpecialPriceConstraint;

/**
 * @ORM\Entity(repositoryClass=ProductRepositoryInterface::class)
 * @ORM\Table(name="product")
 */
class Product
{
    /**
     * @var string
     *
     * @ORM\Id
     * @ORM\Column(type="string", length=128, unique=true, nullable=false)
     * @Constraints\NotBlank(message="SKU is blank")
     *
     */
    private $SKU;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=256, nullable=false)
     * @Constraints\NotBlank(message="description is blank")
     */
    private $description;

    /**
     * @var float
     *
     * @ORM\Column(type="float", nullable=false)
     * @Constraints\NotBlank(message="price is blank")
     * @Constraints\Positive(message="price isn't positive")
     * @Constraints\Regex(pattern="/(?:\d+|\d*\.\d+)/", message="price is invalid")
     */
    private $price;

    /**
     * @ORM\Column(type="float", nullable=true)
     *
     * @SpecialPriceConstraint
     * @Constraints\Positive(message="special price isn't positive")
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
    public function __construct(string $SKU, string $description, float $price, ?float $specialPrice)
    {
        $this->SKU = $SKU;
        $this->description = $description;
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
     * @return string
     */
    public function description(): string
    {
        return $this->description;
    }

    /**
     * @return float
     */
    public function price(): float
    {
        return $this->price;
    }

    /**
     * @return float|null
     */
    public function specialPrice(): ?float
    {
        return $this->specialPrice;
    }

    /**
     * @param string $description
     */
    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    /**
     * @param float $price
     */
    public function setPrice(float $price): void
    {
        $this->price = $price;
    }

    /**
     * @param float|null $specialPrice
     */
    public function setSpecialPrice(?float $specialPrice): void
    {
        $this->specialPrice = $specialPrice;
    }
}
