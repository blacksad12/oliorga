<?php

namespace Finance\OperationBundle\Service\Helper;

use Finance\OperationBundle\Entity\Category;

/**
 * CategoryHelper
 */
class CategoryHelper
{
    protected $em;
    protected $doctrine;
    protected $operationHelper;
    
    /** ************************************************************************
     * Constructor
     * @param \Doctrine\ORM\EntityManager $em
     **************************************************************************/
    public function __construct(\Doctrine\ORM\EntityManager $em, $doctrine, \Finance\OperationBundle\Service\Helper\OperationHelper $operationHelper) {
        $this->em               = $em;
        $this->doctrine         = $doctrine;
        $this->operationHelper  = $operationHelper;
    }
    
    /** ************************************************************************
     * Only 2 level
     * @param array $parameters
     * @return array
     **************************************************************************/
    public function getBalanceHistoryPerMonth(array $parameters) {
        $balanceHistoryPerMonth = array();
        $childrenCategories     = $parameters['category']->getChildrenCategories();
        
        if(count($childrenCategories) === 0) {
            $balanceHistoryPerMonth = $this->operationHelper->getBalanceHistoryPerMonth(array('category' => $parameters['category']));
        }
        else {
            foreach($childrenCategories as $childCategory) {
                $parameters['category'] = $childCategory;
                $balanceHistoryPerMonth[$childCategory->getId()] = $this->operationHelper->getBalanceHistoryPerMonth($parameters);
            } 
        }
        
        return $balanceHistoryPerMonth;
    }
    
    
    
    /** ************************************************************************
     * 
     * @param array $parameters
     * @return array
     **************************************************************************/
    public function getBalancePerCategory(array $parameters) {
        $categories = $this->doctrine->getRepository('FinanceOperationBundle:Category')->findAll();
        $soldeParCategorie = array();
        foreach($categories as $categorie) {
            $parameters['categorie'] = $categorie;
            $solde = $this->em->getRepository('FinanceOperationBundle:Operation')->getBalance($parameters);
            if($solde !== NULL) {
                $soldeParCategorie[($solde > 0 ? 'revenus' : 'depenses')][$categorie->__toString()] = $this->em->getRepository('FinanceOperationBundle:Operation')->getBalance($parameters);
            }            
        }
        return $soldeParCategorie;
    }
    
    /** ************************************************************************
     * 
     * @param array $parameters
     * @return array
     **************************************************************************/
    public function getMonthlyMeans(array $parameters) {
        $monthlyMeans = array();
        
        $monthlyMeans['current'] = $this->operationHelper->getMonthlyMeans($parameters);
        foreach($parameters['category']->getChildrenCategories() as $childCategory) {
            $parameters['category'] = $childCategory;
            $monthlyMeans['children'][$childCategory->getId()] = $this->operationHelper->getMonthlyMeans($parameters);
        }
        
        return $monthlyMeans;
    }
    
    
    
}
