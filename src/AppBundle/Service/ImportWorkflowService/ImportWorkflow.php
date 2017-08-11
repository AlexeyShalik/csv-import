<?php

namespace AppBundle\Service\ImportWorkflowService;

use League\Csv\Reader;
use Doctrine\ORM\EntityManager;
use Symfony\Component\DependencyInjection\Container;

class ImportWorkflow implements ImportWorkflowInterface
{
    private $em;
    private $success;
    private $allRows;
    private $error;
    private $container;

    public function __construct(EntityManager $em, Container $container)
    {
        $this->container = $container;
        $this->em = $em;
        $this->error = [];
        $this->success = [];
    }

    public function process()
    {
        $csv = Reader::createFromPath('web/stock/stock.csv');
        $csv->setOffset(1);
        $this->allRows = count($csv->fetchAll());
        
        $validator = $this->container->get('validator');
        $createProduct = $this->container->get('create_product');
        $rulesFilter = $this->container->get('rules_filter');

        $keys = [
            'Product Code',
            'Product Name',
            'Product Description',
            'Stock',
            'Cost in GBP',
            'Discontinued'
        ];

        $handleRow = function ($row) use ($validator) {
            $row['Stock'] = $validator->getStockConverter($row['Stock']);
            $row['Cost in GBP'] = $validator->getCostConverter($row['Cost in GBP']);
            $row['Discontinued'] = $validator->getDiscontinuedConverter($row['Discontinued']);
            return $row;
        };

        foreach ($csv->setOffset(1)->fetchAssoc($keys, $handleRow) as $row) {
            $rulesFilter->process($row);
        }

        $this->error = $rulesFilter->getError();
        $this->success = $rulesFilter->getSuccess();
        
        foreach ($this->success as $row)
        {
            $product = $createProduct->createProduct($row);
            $this->em->persist($product);
        }

        $this->em->flush();
    }

    public function getSuccessCount()
    {
        return count($this->success);
    }
    
    public function getTotalRowsCount()
    {
        return $this->allRows;
    }

    public function getError()
    {
        return $this->error;
    }
}