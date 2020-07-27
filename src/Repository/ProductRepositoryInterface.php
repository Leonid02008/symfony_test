<?php

namespace App\Repository;

use App\Entity\Product;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Exception;

/**
 * Interface ProductRepositoryInterface
 *
 * @package App\Repository
 *
 * @method Product|null find($id, $lockMode = null, $lockVersion = null)
 * @method Product|null findOneBy(array $criteria, array $orderBy = null)
 * @method Product[]    findAll()
 * @method Product[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
interface ProductRepositoryInterface
{

    /**
     * @param Product $product
     *
     * @throws Exception
     */
    public function save(Product $product): void;

    /**
     * @param Product $product
     *
     * @throws Exception
     */
    public function persist(Product $product): void;

    /**
     * @throws Exception
     */
    public function flush(): void;
}
