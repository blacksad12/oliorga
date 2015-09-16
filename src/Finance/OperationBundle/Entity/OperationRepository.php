<?php

namespace Finance\OperationBundle\Entity;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;

/**
 * OperationRepository
 */
class OperationRepository extends EntityRepository
{
    /** ************************************************************************
     * Get the balance of all the operation which match the criteria in $parameters
     * @param array $parameters
     * @return float
     **************************************************************************/
    public function getBalance(array $parameters) {        
        $this->checkParameters($parameters);
        
        $em = $this->getEntityManager();
        $qb = $em->createQueryBuilder();
        
        $qb->select('SUM(o.amount) as balance');        
        $qb->from('FinanceOperationBundle:Operation','o'); 
        
        $this->filterByCategory($qb, $parameters);
        $this->filterByImputation($qb, $parameters);
        $this->filterByStakeholder($qb, $parameters);
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
        
        $qb->select('o');
        $qb->from('FinanceOperationBundle:Operation','o');        
        
        $this->filterByCategory($qb, $parameters);
        $this->filterByImputation($qb, $parameters);
        $this->filterByStakeholder($qb, $parameters);
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
            if(     $parameterKey == 'category'             && $parameterValue instanceof Category){}
            elseif( $parameterKey == 'imputation'           && $parameterValue instanceof Imputation){}
            elseif( $parameterKey == 'stakeholder'          && $parameterValue instanceof Stakeholder){}
            elseif( $parameterKey == 'account'              && $parameterValue instanceof \Finance\AccountBundle\Entity\Account){}
            elseif( $parameterKey == 'before'               && $parameterValue instanceof \DateTime){}
            elseif( $parameterKey == 'after'                && $parameterValue instanceof \DateTime){}
            elseif( $parameterKey == 'isMarked'             && is_bool($parameterValue)){}
            elseif( $parameterKey == 'isMonthlyRecurrent'   && is_bool($parameterValue)){}
            else{
                throw new \InvalidArgumentException("The parameter with key: '".$parameterKey."' is not valid");
            }
        }
    }

    /** ************************************************************************
     * Filter by Category
     * @param QueryBuilder $qb
     * @param array $parameters
     **************************************************************************/
    protected function filterByCategory(QueryBuilder $qb, array $parameters) {
        if(isset($parameters['category'])) {
            $qb->leftJoin('o.category', 'c');
            $qb->leftJoin('c.parent', 'pc');
            $qb->andWhere($qb->expr()->orX(
                    $qb->expr()->eq('o.category', ':category'),
                    $qb->expr()->eq('c.parent', ':category'),
                    $qb->expr()->eq('pc.parent', ':category')
                    ));
            $qb->setParameter('category', $parameters['category']);
        }
    }
    
    /** ************************************************************************
     * Filter by Imputation
     * @param QueryBuilder $qb
     * @param array $parameters
     **************************************************************************/
    protected function filterByImputation(QueryBuilder $qb, array $parameters) {
        if(isset($parameters['imputation'])) {
            $qb->leftJoin('o.imputation', 'i');
            $qb->leftJoin('i.parent', 'pi');
            $qb->andWhere($qb->expr()->orX(
                    $qb->expr()->eq('o.imputation', ':imputation'),
                    $qb->expr()->eq('i.parent', ':imputation'),
                    $qb->expr()->eq('pi.parent', ':imputation')
                    ));
            $qb->setParameter('imputation', $parameters['imputation']);
        }
    }
    
    /** ************************************************************************
     * Filter by Stakeholder
     * @param QueryBuilder $qb
     * @param array $parameters
     **************************************************************************/
    protected function filterByStakeholder(QueryBuilder $qb, array $parameters) {
        if(isset($parameters['stakeholder'])) {
            $qb->leftJoin('o.stakeholder', 's');
            $qb->leftJoin('s.parent', 'ps');
            $qb->andWhere($qb->expr()->orX(
                    $qb->expr()->eq('o.stakeholder', ':stakeholder'),
                    $qb->expr()->eq('s.parent', ':stakeholder'),
                    $qb->expr()->eq('ps.parent', ':stakeholder')
                    ));
            $qb->setParameter('stakeholder', $parameters['stakeholder']);
        }
    }
    
    /** ************************************************************************
     * Filter by Account
     * @param QueryBuilder $qb
     * @param array $parameters
     **************************************************************************/
    protected function filterByAccount(QueryBuilder $qb, array $parameters) {
        if(isset($parameters['account'])) {
            $qb->andWhere($qb->expr()->eq('o.account', ':account'));
            $qb->setParameter('account', $parameters['account']);
        }
    }
    
    /** ************************************************************************
     * Filter the result which are after startDate and/or before endDate
     * @param QueryBuilder $qb
     * @param array $parameters
     **************************************************************************/
    protected function filterByDate(QueryBuilder $qb, array $parameters) {
        if(isset($parameters['after'])) {
            $qb->andWhere($qb->expr()->gte('o.date', ':after'));
            $qb->setParameter('after', $parameters['after']);
        }
        
        if(isset($parameters['before'])) {
            $qb->andWhere($qb->expr()->lte('o.date', ':before'));
            $qb->setParameter('before', $parameters['before']);
        }
    }
    
    /** ************************************************************************
     * Filter the result which are pointé or not pointé
     * @param QueryBuilder $qb
     * @param array $parameters
     **************************************************************************/
    protected function filterByMarked(QueryBuilder $qb, array $parameters) {
        if(isset($parameters['isMarked'])) {
            $qb->andWhere($qb->expr()->eq('o.isMarked', ':isMarked'));
            $qb->setParameter('isMarked', $parameters['isMarked']);
        }         
    }
    
    /** ************************************************************************
     * Filter the result which are monthly recurrent or not
     * @param QueryBuilder $qb
     * @param array $parameters
     **************************************************************************/
    protected function filterByMonthlyRecurrent(QueryBuilder $qb, array $parameters) {
        $qb->andWhere($qb->expr()->eq('o.isMonthlyRecurrent', ':isMonthlyRecurrent'));
        if(isset($parameters['isMonthlyRecurrent'])) {
            $qb->setParameter('isMonthlyRecurrent', $parameters['isMonthlyRecurrent']);
        } else {
            $qb->setParameter('isMonthlyRecurrent', false);
        }
    }
    
    /** ************************************************************************
     * Join Categorys if not already joined
     * @param \Doctrine\ORM\QueryBuilder $qb
     **************************************************************************/
    protected function join($join, $alias, QueryBuilder $qb) {
        if(!$this->isAlreadyJoined($qb, $alias)){
            $qb->innerJoin($join, $alias);            
        }
    }
    
    /** ************************************************************************
     * Return true if the $alias is already joined in the $qb
     * @param \Doctrine\ORM\QueryBuilder $qb
     * @param string $alias
     * @return boolean
     **************************************************************************/
    protected function isAlreadyJoined(QueryBuilder $qb, $alias) {
        $joins = $qb->getDQLPart('join');
        if(empty($joins)){
            return false;
        }
        
        $aliasesJoined = array();        
        foreach($joins['o'] as $join){
            $aliasesJoined[] = $join->getAlias();
        }
        return in_array($alias, $aliasesJoined);
    }
}
