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
    private $keys;
    private $skipped;
    private $file;
    private $testMode = false;

    /**
     * Initializes.
     *
     * @param $filePath
     */
    public function initialize($filePath)
    {
        $this->file = $filePath;
    }

    public function __construct(EntityManager $em, Container $container)
    {
        $this->container = $container;
        $this->em = $em;
        $this->error = [];
        $this->success = [];
        $this->skipped = [];
        $this->keys = [
            'Product Code',
            'Product Name',
            'Product Description',
            'Stock',
            'Cost in GBP',
            'Discontinued'
        ];
    }

    /**
     * Executes import process.
     *
     * return $this
     */
    public function process()
    {
        $csv = Reader::createFromPath($this->file);
        $this->allRows = count($csv->setOffset(1)->fetchAll());
        
        $validator = $this->container->get('validator');
        $createProduct = $this->container->get('create_product');
        $rulesFilter = $this->container->get('rules_filter');
        
        $handleRow = function ($row) use ($validator) {
            $row['Stock'] = $validator->getStockConverter($row['Stock']);
            $row['Cost in GBP'] = $validator->getCostConverter($row['Cost in GBP']);
            $row['Discontinued'] = $validator->getDiscontinuedConverter($row['Discontinued']);
            return $row;
        };

        foreach ($csv->setOffset(1)->fetchAssoc($this->keys, $handleRow) as $row) {
            $rulesFilter->process($row);
        }
        
        $this->error = $rulesFilter->getError();
        $this->success = $rulesFilter->getSuccess();
        $this->skipped = $rulesFilter->getSkipped();
        
        if ($this->testMode != true) {
            foreach ($this->success as $row) {
                $product = $createProduct->createProduct($row);
                $this->em->persist($product);
            }

            $this->em->flush();
        }
        
        return $this;
    }

    /**
     * Enables "test" mode: data is processed in the same way, but not inserted into a database.
     *
     * @param $mode
     *
     * @return $this
     */
    public function setTestMode($mode)
    {
        $this->testMode = $mode;
        return $this;
    }
    
    /**
     * @return int
     */
    public function getSuccessCount()
    {
        return count($this->success);
    }

    /**
     * @return int
     */
    public function getTotalRowsCount()
    {
        return $this->allRows;
    }

    /**
     * @return array
     */
    public function getError()
    {
        return $this->error;
    }

    /**
     * @return array
     */
    public function getSkipped()
    {
        return $this->skipped;
    }
}
