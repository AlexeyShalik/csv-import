<?php 

namespace AppBundle\Service\ValidatorService;

use Symfony\Component\DependencyInjection\Container;

class ValidatorService implements ValidatorServiceInterface
{
    public function test()
    {
        return 'test';
    }

    /**
     * Returns \DateTime() or null, which transforms 'discountinued' field.
     *
     * @return \Closure
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
     * @return \Closure
     */
    public function getCostConverter($input)
    {
            $matches = [];
            preg_match('#([0-9\.]+)#', $input, $matches);
            return (count($matches) > 0) ? floatval($matches[0]) : 0;
    }
}