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
    private $container;

    public function __construct(EntityManager $em, Container $container)
    {
        $this->container = $container;
        $this->em = $em;
    }

    public function process()
    {

        $csv = Reader::createFromPath('web/stock/test.csv');
        $rows = $csv->fetchAll();
        $this->allRows = count($rows);
        $validator = $this->container->get('validator');

        foreach ($rows as $data) {
            $product = new Product();
            $product->setCode(
                $data[0]
            );
            $product->setName(
                $data[1]
            );
            $product->setDescription(
                $data[2]
            );
            $product->setStock(
                $data[3]
            );
            $product->setCost(
                $validator->getCostConverter($data[4])
            );
            $product->setDiscontinued(
                $validator->getDiscontinuedConverter($data[5])
            );
            $product->setAdded(
                new \DateTime()
            );
            $this->em->persist($product);
        }
        $this->em->flush();


        return $rows; 
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