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
        $this->filterByDate($qb, $parameters);
        $this->filterByMarked($qb, $parameters);
               
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
        $this->filterByDate($qb, $parameters);
        $this->filterByMarked($qb, $parameters);
               
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
            if(     $parameterKey == 'category'         && $parameterValue instanceof Category){}
            elseif( $parameterKey == 'imputation'       && $parameterValue instanceof Imputation){}
            elseif( $parameterKey == 'startDate'        && $parameterValue instanceof \DateTime){}
            elseif( $parameterKey == 'endDate'          && $parameterValue instanceof \DateTime){}
            elseif( $parameterKey == 'isMarked'         && is_bool($parameterValue)){}
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
            $qb->andWhere($qb->expr()->orX(
                    $qb->expr()->eq('o.category', ':category'),
                    $qb->expr()->eq('c.parent', ':category'))
                    );
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
            $qb->andWhere($qb->expr()->eq('o.imputation', ':imputation'));
            $qb->setParameter('imputation', $parameters['imputation']);
        }
    }
    
    /** ************************************************************************
     * Filter the result which are after startDate and/or before endDate
     * @param QueryBuilder $qb
     * @param array $parameters
     **************************************************************************/
    protected function filterByDate(QueryBuilder $qb, array $parameters) {
        if(isset($parameters['startDate'])) {
            $qb->andWhere($qb->expr()->gte('o.date', ':startDate'));
            $qb->setParameter('startDate', $parameters['startDate']);
        }
        
        if(isset($parameters['endDate'])) {
            $qb->andWhere($qb->expr()->lte('o.date', ':endDate'));
            $qb->setParameter('endDate', $parameters['endDate']);
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
