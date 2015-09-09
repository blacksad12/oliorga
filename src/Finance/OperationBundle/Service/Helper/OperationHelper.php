<?php

namespace Finance\OperationBundle\Service\Helper;

use Finance\OperationBundle\Entity\Operation;

/**
 * OperationHelper
 */
class OperationHelper extends AbstractOperationHelper
{
    protected $em;
    protected $doctrine;
    
    /** ************************************************************************
     * Constructor
     * @param \Doctrine\ORM\EntityManager $em
     **************************************************************************/
    public function __construct(\Doctrine\ORM\EntityManager $em, $doctrine) {
        $this->em       = $em;
        $this->doctrine = $doctrine;
    }
    
    /** ************************************************************************
     * Duplicate an Operation
     * 
     * @param \Finance\OperationBundle\Entity\Operation $oldOperation
     * @param \Finance\OperationBundle\Entity\Operation $newOperation
     **************************************************************************/
    public function duplicate(Operation $oldOperation, Operation $newOperation) {
        parent::duplicateAbstractOperation($oldOperation, $newOperation);        
        $newOperation->setAccount($oldOperation->getAccount());
        $newOperation->setCategory($oldOperation->getCategory());
        $newOperation->setStakeholder($oldOperation->getStakeholder());
        $newOperation->setImputation($oldOperation->getImputation());
        $newOperation->setPaymentMethod($oldOperation->getPaymentMethod());
        $newOperation->setComment($oldOperation->getComment());
    }
    
    /** ************************************************************************
     * Get an array with the balance for each month of the Operations matching 
     * criteria defined in $parameters
     * 0 => [
     *      ['balance'] => float,
     *      ['date']    => /DateTime,
     * ],
     * 1 => etc.
     * @param array $parameters
     * @return array
     **************************************************************************/
    public function getMonthlyBalanceHistory(array $parameters) {
        $startDate  = new \DateTime("2008-09-30");
        $endDate    = new \DateTime();
        $endOfMonth = $startDate;
        $i = 0;
        
        $monthlyBalanceHistory = array();
        
        while($endOfMonth < $endDate){
            $startOfMonth = clone $endOfMonth;
            $startOfMonth->modify('first day of this month'); 
            
            $parameters['startDate']                = $startOfMonth;
            $parameters['endDate']                  = $endOfMonth;
            $balance                                = $this->em->getRepository('FinanceOperationBundle:Operation')->getBalance($parameters);
            $monthlyBalanceHistory[$i]['balance']   = $balance === NULL ? 0 : floatval($balance);
            $monthlyBalanceHistory[$i]['date']      = clone $endOfMonth;
            
            $endOfMonth->modify('last day of next month');
            $i++;
        }
        return $monthlyBalanceHistory;
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
     * Get an array containing the monthly means of the amount of each Operation 
     * matching $parameters, for the following timeframes:
     * - ['one']      => from first day of last month to last day of last month
     * - ['three']    => from first day of 3 month ago to last day of last month
     * - ['six']      => from first day of 6 month ago to last day of last month
     * - ['thisYear'] => from 1st January this year to 31st December this year
     * - ['lastYear'] => from 1st January last year to 31st December last year
     * @param array $parameters
     * @return array
     **************************************************************************/
    public function getMonthlyMeans(array $parameters) {
        return array(
            'one'       => $this->getLastMonthMonthlyMean($parameters),
            'three'     => $this->getLastXMonthsMonthlyMean($parameters,3),
            'six'       => $this->getLastXMonthsMonthlyMean($parameters,6),
            'thisYear'  => $this->getThisYearMonthlyMean($parameters),
            'lastYear'  => $this->getLastYearMonthlyMean($parameters),
        );
    }
    
    /** ************************************************************************
     * GetLastMonthMonthlyMean
     * @param array $parameters
     * @return float
     **************************************************************************/
    public function getLastMonthMonthlyMean(array $parameters) {
        $parameters['startDate'] = new \DateTime("first day of last month");
        $parameters['endDate']   = new \DateTime("last day of last month");
        return $this->em->getRepository('FinanceOperationBundle:Operation')->getBalance($parameters);
    }
    
    /** ************************************************************************
     * GetLastXMonthsMonthlyMean
     * @param array $parameters
     * @return float
     **************************************************************************/
    public function getLastXMonthsMonthlyMean(array $parameters, $numberOfMonths) {
        $parameters['startDate'] = new \DateTime("first day of ".$numberOfMonths." month ago");
        $parameters['endDate']   = new \DateTime("last day of last month");
        return ($this->em->getRepository('FinanceOperationBundle:Operation')->getBalance($parameters))/$numberOfMonths;
    }
    
    /** ************************************************************************
     * GetThisYearMonthlyMean
     * @param array $parameters
     * @return float
     **************************************************************************/
    public function getThisYearMonthlyMean(array $parameters) {
        $parameters['startDate'] = new \DateTime("first day of January this year");
        $parameters['endDate']   = new \DateTime("last day of December this year");
        $now = new \DateTime;
        $numberOfDaysSinceBeginning = ($now->diff($parameters['startDate'])->days);
        return ($this->em->getRepository('FinanceOperationBundle:Operation')->getBalance($parameters))/($numberOfDaysSinceBeginning/30);
    }
    
    /** ************************************************************************
     * GetLastYearMonthlyMean
     * @param array $parameters
     * @return float
     **************************************************************************/
    public function getLastYearMonthlyMean(array $parameters) {
        $parameters['startDate'] = new \DateTime("first day of January last year");
        $parameters['endDate']   = new \DateTime("last day of December last year");
        return ($this->em->getRepository('FinanceOperationBundle:Operation')->getBalance($parameters))/12;
    }
    
    
}
