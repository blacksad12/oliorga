<?php

namespace Finance\OperationBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class OperationType extends AbstractType
{
        
    /** ************************************************************************
     * @param FormBuilderInterface $builder
     * @param array $options
     **************************************************************************/
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('date', 'date', array(
                'required'  => true,
                'widget'    => 'single_text',
                'input'     => 'datetime',
                'format'    => 'dd/MM/yyyy',
                'attr'      => array('class' => 'dateonly'),
            ))            
            ->add('amount', 'float', array(
                'required'  => true,
            ))            
            ->add('comment', 'text', array(
                'required'  => false,
            ))                    
            ->add('category', 'entity', array(
                'class'         => "FinanceOperationBundle:Category",
                'required'      => false,
                'query_builder' => function(\Finance\OperationBundle\Entity\CategoryRepository $r) {
                        return $r->createQueryBuilder('c')
                                ->where('c.parent IS NOT NULL')
                                ->andWhere('c.isObselete = TRUE')
                                ->leftjoin('c.parent','p')
                                ->orderBy('p.name')
                                ->addOrderBy('c.name')
                                ;}
            ))                    
            ->add('imputation', 'entity', array(
                'class'         => "FinanceOperationBundle:Imputation",
                'required'      => false,
                'query_builder' => function(\Finance\OperationBundle\Entity\ImputationRepository $r) {
                        return $r->createQueryBuilder('i')
                                ->where('i.parent IS NOT NULL')
                                ->leftjoin('i.parent','p')
                                ->orderBy('p.name')
                                ->addOrderBy('i.name')
                                ;}
            ))                    
            ->add('stakeholder', 'entity', array(
                'class'         => "FinanceOperationBundle:Stakeholder",
                'required'      => false,
                'query_builder' => function(\Finance\OperationBundle\Entity\StakeholderRepository $r) {
                        return $r->createQueryBuilder('s')
                                ->where('s.parent IS NOT NULL')
                                ->leftjoin('s.parent','p')
                                ->orderBy('p.name')
                                ->addOrderBy('s.name')
                                ;}
            ))                    
            ->add('paymentMethod', 'entity', array(
                'class'         => "FinanceOperationBundle:PaymentMethod",
                'required'      => false,
                'query_builder' => function(\Finance\OperationBundle\Entity\PaymentMethodRepository $r) {
                        return $r->createQueryBuilder('p')
                                ->orderBy('p.name')
                                ;}
            ))
            ->add('isMarked', 'checkbox', array(
                'required'  => false,
                'label'     => 'Marked',
            ))
            
            ;
    }
    
    /** ************************************************************************
     * @param OptionsResolver $resolver
     **************************************************************************/
    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults(array(
            'data_class' => 'Finance\OperationBundle\Entity\Operation'
        ));
    }

    /** ************************************************************************
     * @return string
     **************************************************************************/
    public function getName() {
        return 'finance_operationbundle_operation';
    }
}
