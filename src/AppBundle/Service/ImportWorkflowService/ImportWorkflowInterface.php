<?php

namespace AppBundle\Service\ImportWorkflowService;

interface ImportWorkflowInterface
{
    /**
     * Initializes.
     *
     * @param $filePath
     */
    public function initialize($filePath);

    /**
     * Executes import process.
     */
    public function process();

    /**
     * @return int
     */
    public function getSuccessCount();

    /**
     * @return int
     */
    public function getTotalRowsCount();

    /**
     * @return array
     */
    public function getError();

    /**
     * @return array
     */
    public function getSkipped();
}