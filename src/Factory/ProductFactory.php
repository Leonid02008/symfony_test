<?php

namespace App\Factory;

use App\DTO\Product as DTOProduct;
use App\Entity\Product;

/**
 * Class ProductFactory
 * @package App\Factory
 */
class ProductFactory implements ProductFactoryInterface
{
    /**
     * {@inheritdoc}
     */
    public function create(DTOProduct $data): Product
    {
        return new Product(
            $data->SKU(),
            $data->description(),
            $data->price(),
            $data->specialPrice()
        );
    }
}