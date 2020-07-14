<?php

namespace App\Factory;

use App\DTO\Product as DTOProduct;
use App\Entity\Product;

/**
 * Interface ProductFactoryInterface
 * @package App\Factory
 */
interface ProductFactoryInterface
{
    /**
     * @param DTOProduct $data
     *
     * @return Product
     */
    public function create(DTOProduct $data): Product;
}
