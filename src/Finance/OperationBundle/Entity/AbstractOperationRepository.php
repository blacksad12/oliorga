<?php

namespace Finance\OperationBundle\Entity;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;

/**
 * AbstractOperationRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class AbstractOperationRepository extends EntityRepository
{
    
    /** ************************************************************************
     * Get the balance of all the AbstractOperation which match the criteria in $parameters
     * @param array $parameters
     * @return int
     **************************************************************************/
    public function getBalance(array $parameters) {        
        $this->checkParameters($parameters);
        
        $em = $this->getEntityManager();
        $qb = $em->createQueryBuilder();
        
        $qb->select('SUM(COALESCE(o.amount,0)) + SUM(COALESCE(it.amount,0)) - SUM(COALESCE(ot.amount,0)) as balance');
        $qb->from('FinanceOperationBundle:AbstractOperation','ao');        
                
        $this->filterByAccount($qb, $parameters);
        $this->filterByDate($qb, $parameters);
        $this->filterByMarked($qb, $parameters);
        $this->filterByMonthlyRecurrent($qb, $parameters);
             
        $result = $qb->getQuery()->getSingleResult();
        return $result['balance'];
    }
    
    /** ************************************************************************
     * Get all the operations which match the criteria in $parameters
     * @param array $parameters
     * @return Operation[]
     **************************************************************************/
    public function getOperations(array $parameters) {
        $this->checkParameters($parameters);
        
        $em = $this->getEntityManager();
        $qb = $em->createQueryBuilder();
        
        $qb->select('ao');
        $qb->from('FinanceOperationBundle:AbstractOperation','ao');        
        
        $this->filterByAccount($qb, $parameters);
        $this->filterByDate($qb, $parameters);
        $this->filterByMarked($qb, $parameters);
        $this->filterByMonthlyRecurrent($qb, $parameters);
               
        return $qb->getQuery()->getResult();
    }
    
    
    /** ************************************************************************
     * Check if all the parameters are recognized by the class
     * 
     * @param array $parameters
     * @throws \InvalidArgumentException
     **************************************************************************/
    protected function checkParameters(array $parameters) {
        foreach($parameters as $parameterKey=>$parameterValue){
            if(     $parameterKey == 'before'               && $parameterValue instanceof \DateTime){}
            elseif( $parameterKey == 'after'                && $parameterValue instanceof \DateTime){}
            elseif( $parameterKey == 'isMarked'             && is_bool($parameterValue)){}
            elseif( $parameterKey == 'account'              && $parameterValue instanceof \Finance\AccountBundle\Entity\Account){}
            elseif( $parameterKey == 'isMonthlyRecurrent'   && is_bool($parameterValue)){}
            else{
                throw new \InvalidArgumentException("The parameter with key: '".$parameterKey."' is not valid");
            }
        }
    }

    /** ************************************************************************
     * Filter the result which are after startDate and/or before endDate
     * @param QueryBuilder $qb
     * @param array $parameters
     **************************************************************************/
    protected function filterByAccount(QueryBuilder $qb, array $parameters) {
        if(isset($parameters['account'])) {
            if($parameters['account'] == NULL){
                $qb->leftJoin('FinanceOperationBundle:Operation', 'o','WITH',$qb->expr()->eq('ao.id', 'o.id'));
                $qb->leftJoin('FinanceOperationBundle:TransferBetweenAccount', 'it','WITH',$qb->expr()->eq('ao.id', 'it.id'));
                $qb->leftJoin('FinanceOperationBundle:TransferBetweenAccount', 'ot','WITH',$qb->expr()->eq('ao.id', 'ot.id'));
            }
            else{            
               $qb->leftJoin('FinanceOperationBundle:Operation', 'o','WITH',$qb->expr()->andX(
                            $qb->expr()->eq('ao.id', 'o.id'),
                            $qb->expr()->eq('o.account', ':account')))
                         ->setParameter('account', $parameters['account'])
                    ->leftJoin('FinanceOperationBundle:TransferBetweenAccount', 'it','WITH',$qb->expr()->andX(
                            $qb->expr()->eq('ao.id', 'it.id'),
                            $qb->expr()->eq('it.destinationAccount', ':account')))
                         ->setParameter('account', $parameters['account'])
                    ->leftJoin('FinanceOperationBundle:TransferBetweenAccount', 'ot','WITH',$qb->expr()->andX(
                            $qb->expr()->eq('ao.id', 'ot.id'),
                            $qb->expr()->eq('ot.sourceAccount', ':account')))
                         ->setParameter('account', $parameters['account'])
                         ;
            }
        }        
    }
    
    /** ************************************************************************
     * Filter the result which are after $parameters['after'] and/or before
     * $parameters['before']
     * @param QueryBuilder $qb
     * @param array $parameters
     **************************************************************************/
    protected function filterByDate(QueryBuilder $qb, array $parameters) {
        if(isset($parameters['after'])) {
            $qb->andWhere($qb->expr()->gte('ao.date', ':after'));
            $qb->setParameter('after', $parameters['after']);
        }
        
        if(isset($parameters['before'])) {
            $qb->andWhere($qb->expr()->lte('ao.date', ':before'));
            $qb->setParameter('before', $parameters['before']);
        }
    }
    
    /** ************************************************************************
     * Filter the result which are marked or not marked
     * @param QueryBuilder $qb
     * @param array $parameters
     **************************************************************************/
    protected function filterByMarked(QueryBuilder $qb, array $parameters) {
        if(isset($parameters['isMarked'])) {
            $qb->andWhere($qb->expr()->eq('ao.isMarked', ':isMarked'));
            $qb->setParameter('isMarked', $parameters['isMarked']);
        }         
    }
    
    /** ************************************************************************
     * Filter the result which are monthly recurrent or not
     * @param QueryBuilder $qb
     * @param array $parameters
     **************************************************************************/
    protected function filterByMonthlyRecurrent(QueryBuilder $qb, array $parameters) {
        $qb->andWhere($qb->expr()->eq('ao.isMonthlyRecurrent', ':isMonthlyRecurrent'));
        if(isset($parameters['isMonthlyRecurrent'])) {
            $qb->setParameter('isMonthlyRecurrent', $parameters['isMonthlyRecurrent']);
        } else {
            $qb->setParameter('isMonthlyRecurrent', false);
        }
    }
    
}
