<?php

namespace AppBundle\Service\CreateProductService;

interface CreateProductServiceInterface
{
    /**
     * Create product object.
     *
     * @param $row
     *
     * @return object
     */
    public function createProduct($row);
}
