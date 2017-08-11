<?php

namespace AppBundle\Service\ImportWorkflowService;

use League\Csv\Reader;
use Doctrine\ORM\EntityManager;
use AppBundle\Entity\Product;
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
        $this->success = 0;
    }

    public function process()
    {

        $csv = Reader::createFromPath('web/stock/stock.csv');
        $csv->setOffset(1);
        
        $this->allRows = count($csv->fetchAll());
        
        $keys = [
            'Product Code',
            'Product Name',
            'Product Description',
            'Stock',
            'Cost in GBP',
            'Discontinued'
        ];

        $validator = $this->container->get('validator');
        
        $handleRow = function ($row) use ($validator) {
            $row['Stock'] = $validator->getStockConverter($row['Stock']);
            $row['Cost in GBP'] = $validator->getCostConverter($row['Cost in GBP']);
            $row['Discontinued'] = $validator->getDiscontinuedConverter($row['Discontinued']);
            return $row;
        };
        
        foreach ($csv->fetchAssoc($keys, $handleRow) as $row) {
            $product = new Product();
            try {
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
                $this->em->persist($product);
                $this->em->flush();
            }
            catch (\Exception $e){
                array_push($this->error, $row);
            }
        }
        $this->success = $this->allRows - count($this->error) + 1;
        echo count($this->error);
    }

    public function getSuccessCount()
    {
        return $this->success;
    }
    
    public function getTotalRowsCount()
    {
        return $this->allRows;
    }


}