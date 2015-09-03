<?php

namespace Finance\OperationBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TransferBetweenAccountType extends AbstractType
{
        
    /** ************************************************************************
     * @param FormBuilderInterface $builder
     * @param array $options
     **************************************************************************/
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
                    
            ->add('sourceAccount', 'entity', array(
                'class'         => "FinanceAccountBundle:Account",
                'required'      => true,
                'query_builder' => function(\Finance\AccountBundle\Entity\AccountRepository $r) {
                        return $r->createQueryBuilder('s')
                                ;}
            ))                    
            ->add('destinationAccount', 'entity', array(
                'class'         => "FinanceAccountBundle:Account",
                'required'      => true,
                'query_builder' => function(\Finance\AccountBundle\Entity\AccountRepository $r) {
                        return $r->createQueryBuilder('d')
                                ;}
            ))        ;
    }
    
    /** ************************************************************************
     * @param OptionsResolver $resolver
     **************************************************************************/
    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults(array(
            'data_class' => 'Finance\OperationBundle\Entity\TransferBetweenAccount'
        ));
    }

    /** ************************************************************************
     * @return string
     **************************************************************************/
    public function getName() {
        return 'finance_operationbundle_transferbetweenaccount';
    }
}
