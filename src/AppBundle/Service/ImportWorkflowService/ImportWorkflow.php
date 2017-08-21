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
    public function initialize($filePath) : void
    {
        $this->file = $filePath;
    }

    public function __construct(EntityManager $em, Container $container)
    {
        $this->container = $container;
        $this->em = $em;
        $this->error = [];
        $this->skipped = [];
        $this->keys = [
            $this->container->getParameter('product_code'),
            $this->container->getParameter('product_name'),
            $this->container->getParameter('product_description'),
            $this->container->getParameter('stock'),
            $this->container->getParameter('cost_in_GBP'),
            $this->container->getParameter('discontinued'),
        ];
    }

    /**
     * Executes import process.
     *
     * return ImportWorkflow
     */
    public function process() : ImportWorkflow
    {
        $csv = Reader::createFromPath($this->file);
        $this->allRows = count($csv->setOffset(1)->fetchAll());

        $validator = $this->container->get('validator');
        $createProduct = $this->container->get('create_product');
        $rulesFilter = $this->container->get('rules_filter');

        $handleRow = function ($row) use ($validator) {
            $row[$this->container->getParameter('stock')] = $validator->getStockConverter(
                $row[$this->container->getParameter('stock')]
            );
            $row[$this->container->getParameter('cost_in_GBP')] = $validator->getCostConverter(
                $row[$this->container->getParameter('cost_in_GBP')]
            );
            $row[$this->container->getParameter('discontinued')] = $validator->getDiscontinuedConverter(
                $row[$this->container->getParameter('discontinued')]
            );

            return $row;
        };

        $batchInsert = 0;

        foreach ($csv->setOffset(1)->fetchAssoc($this->keys, $handleRow) as $row) {
            $data = $rulesFilter->process($row);
            if (is_array($data)) {
                if ($this->testMode !== true) {
                    $product = $createProduct->createProduct($data);
                    $this->em->persist($product);
                    $batchInsert += 1;
                    if ($batchInsert === 50) {
                        $this->em->flush();
                        $batchInsert = 0;
                    }
                }
            }
        }

        $this->em->flush();

        $this->error = $rulesFilter->getError();
        $this->success = $rulesFilter->getSuccess();
        $this->skipped = $rulesFilter->getSkipped();

        return $this;
    }

    /**
     * Enables "test" mode: data is processed in the same way, but not inserted into a database.
     *
     * @param $mode
     *
     * @return ImportWorkflow
     */
    public function setTestMode(bool $mode) : ImportWorkflow
    {
        $this->testMode = $mode;

        return $this;
    }

    /**
     * @return int
     */
    public function getSuccessCount() : int
    {
        return $this->success;
    }

    /**
     * @return int
     */
    public function getTotalRowsCount() : int
    {
        return $this->allRows;
    }

    /**
     * @return array
     */
    public function getError() : array
    {
        return $this->error;
    }

    /**
     * @return array
     */
    public function getSkipped() : array
    {
        return $this->skipped;
    }
}
