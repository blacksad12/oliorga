<?php

namespace Finance\OperationBundle\Service\Helper;

use Finance\OperationBundle\Entity\Imputation;

/**
 * ImputationHelper
 */
class ImputationHelper
{
    protected $em;
    protected $doctrine;
    protected $operationHelper;
    protected $router;
    
    /** ************************************************************************
     * Constructor
     * @param \Doctrine\ORM\EntityManager $em
     **************************************************************************/
    public function __construct(\Doctrine\ORM\EntityManager $em, $doctrine, \Finance\OperationBundle\Service\Helper\OperationHelper $operationHelper, \Symfony\Component\Routing\Router $router) {
        $this->em               = $em;
        $this->doctrine         = $doctrine;
        $this->operationHelper  = $operationHelper;
        $this->router           = $router;
    }
    
    /** ************************************************************************
     * Return the monthly balance history of the imputation matching the $parameters
     * and its children (if any).
     * ['current'] => [
     *      ['solde'] => float,
     *      ['date']  => /DateTime,
     * ]
     * ['children'] => 
     *      [children_id] => [
     *          ['solde'] => float,
     *          ['date']  => /DateTime,
     *      ]
     * ]
     * 
     * @param array $parameters
     * @return array
     **************************************************************************/
    public function getMonthlyBalanceHistory(array $parameters) {
        $monthlyBalanceHistory = array();
        $monthlyBalanceHistory['current'] = $this->operationHelper->getMonthlyBalanceHistory($parameters);
        foreach($parameters['imputation']->getChildrenImputations() as $childImputation) {
            $parameters['imputation'] = $childImputation;
            $monthlyBalanceHistory['children'][$childImputation->getId()] = $this->operationHelper->getMonthlyBalanceHistory($parameters);
        }
        return $monthlyBalanceHistory;
    }    
    
    /** ************************************************************************
     * Return the monthly means of all the Operations matching the Imputation 
     * described in the $parameters, and its children (if any).
     * ['current'] => [
     *      ['entity'] => Imputation
     *      ['mean'] => [
     *          ['one']   => float,
     *          ['three'] => float,
     *          etc.
     *      ]
     * ]
     * ['children'] => 
     *      [0] => [
     *          ['entity'] => Imputation
     *          ['mean'] => [
     *              ['one']   => float,
     *              ['three'] => float,
     *              etc.
     *          ]
     *      ]
     * ]
     * 
     * @param array $parameters
     * @return array
     **************************************************************************/
    public function getMonthlyMeans(array $parameters) {
        $imputation = $parameters['imputation'];
        $monthlyMeans = array();
        
        $monthlyMeans['current']['entity'] = $imputation;
        $monthlyMeans['current']['means'] = $this->operationHelper->getMonthlyMeans($parameters);
        foreach($imputation->getChildrenImputations() as $key=>$childImputation) {
            $parameters['imputation'] = $childImputation;
            $monthlyMeans['children'][$key]['entity']   = $childImputation;
            $monthlyMeans['children'][$key]['means']    = $this->operationHelper->getMonthlyMeans($parameters);
        }
        return $monthlyMeans;
    }
    
}
