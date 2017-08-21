<?php

namespace AppBundle\Service\ValidatorService;

class ValidatorService implements ValidatorServiceInterface
{
    /**
     * Returns \DateTime() or null, which transforms 'discountinued' field.
     *
     * @param $input
     *
     * @return \DateTime() or null
     */
    public function getDiscontinuedConverter($input)
    {
        if ($input === 'yes') {
            return new \DateTime();
        } else {
            return null;
        }
    }

    /**
     * Returns float number from the input 'cost' string field.
     *
     * @param $input
     *
     * @return float
     */
    public function getCostConverter($input) : float
    {
        $matches = [];
        preg_match('#([0-9\.]+)#', $input, $matches);

        return (count($matches) > 0) ? floatval($matches[0]) : 0;
    }

    /**
     * Returns extracts an integer number from the input 'stock' string field.
     *
     * @param $input
     *
     * @return int or null
     */
    public function getStockConverter($input)
    {
        return (strlen($input) > 0 && is_numeric($input)) ? intval($input) : null;
    }
}
