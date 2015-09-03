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
            
            ->add('comment', 'text', array(
                'required'  => false,
            ))                    
            ->add('account', 'entity', array(
                'class'         => "FinanceAccountBundle:Account",
                'required'      => false,
                'query_builder' => function(\Finance\AccountBundle\Entity\AccountRepository $r) {
                        return $r->createQueryBuilder('a')
                                ;}
            ))                    
            ->add('category', 'entity', array(
                'class'         => "FinanceOperationBundle:Category",
                'required'      => false,
                'query_builder' => function(\Finance\OperationBundle\Entity\CategoryRepository $r) {
                        return $r->createQueryBuilder('c')
                                ;}
            ))                    
            ->add('imputation', 'entity', array(
                'class'         => "FinanceOperationBundle:Imputation",
                'required'      => false,
                'query_builder' => function(\Finance\OperationBundle\Entity\ImputationRepository $r) {
                        return $r->createQueryBuilder('i')
                                ;}
            ))                    
            ->add('stakeholder', 'entity', array(
                'class'         => "FinanceOperationBundle:Stakeholder",
                'required'      => false,
                'query_builder' => function(\Finance\OperationBundle\Entity\StakeholderRepository $r) {
                        return $r->createQueryBuilder('s')
                                ;}
            ))                    
            ->add('paymentMethod', 'entity', array(
                'class'         => "FinanceOperationBundle:PaymentMethod",
                'required'      => false,
                'query_builder' => function(\Finance\OperationBundle\Entity\PaymentMethodRepository $r) {
                        return $r->createQueryBuilder('p')
                                ;}
            ))        ;
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
