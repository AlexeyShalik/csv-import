<?php

namespace AppBundle\Service\CreateProductService;

use AppBundle\Entity\Product;

class CreateProductService implements CreateProductServiceInterface
{
    public function createProduct($row)
    {
        $product = new Product();
        $product->setCode(
            $row['Product Code']
        );
        $product->setName(
            $row['Product Name']
        );
        $product->setDescription(
            $row['Product Description']
        );
        $product->setStock(
            $row['Stock']
        );
        $product->setCost(
            $row['Cost in GBP']
        );
        $product->setDiscontinued(
            $row['Discontinued']
        );
        
        return $product;
    }
}