<?php

namespace Finance\OperationBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ImputationType extends AbstractType
{
        
    /** ************************************************************************
     * @param FormBuilderInterface $builder
     * @param array $options
     **************************************************************************/
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            
            ->add('name', 'text', array(
                'required'  => true,
            ))            
            ->add('startDate', 'date', array(
                'required'  => false,
                'widget'    => 'single_text',
                'input'     => 'datetime',
                'format'    => 'dd/MM/yyyy',
                'attr'      => array('class' => 'dateonly'),
            ))            
            ->add('endDate', 'date', array(
                'required'  => false,
                'widget'    => 'single_text',
                'input'     => 'datetime',
                'format'    => 'dd/MM/yyyy',
                'attr'      => array('class' => 'dateonly'),
            ))                    
            ->add('parent', 'entity', array(
                'class'         => "FinanceOperationBundle:Imputation",
                'required'      => false,
                'query_builder' => function(\Finance\OperationBundle\Entity\ImputationRepository $r) {
                        return $r->createQueryBuilder('p')
                                ;}
            ))        ;
    }
    
    /** ************************************************************************
     * @param OptionsResolver $resolver
     **************************************************************************/
    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults(array(
            'data_class' => 'Finance\OperationBundle\Entity\Imputation'
        ));
    }

    /** ************************************************************************
     * @return string
     **************************************************************************/
    public function getName() {
        return 'finance_operationbundle_imputation';
    }
}
