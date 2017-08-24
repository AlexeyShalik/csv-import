<?php

namespace AppBundle\Service\CreateProductService;

use AppBundle\Entity\Product;
use Symfony\Component\DependencyInjection\Container;

class CreateProductService implements CreateProductServiceInterface
{
    private $container;

    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    /**
     * Create product object.
     *
     * @param $row
     *
     * @return Product
     */
    public function createProduct(array $row) : Product
    {
        $product = new Product();
        $product->setCode(
            $row[$this->container->getParameter('product_code')]
        );
        $product->setName(
            $row[$this->container->getParameter('product_name')]
        );
        $product->setDescription(
            $row[$this->container->getParameter('product_description')]
        );
        $product->setStock(
            $row[$this->container->getParameter('stock')]
        );
        $product->setCost(
            $row[$this->container->getParameter('cost_in_GBP')]
        );
        $product->setDiscontinued(
            $row[$this->container->getParameter('discontinued')]
        );

        return $product;
    }
}
