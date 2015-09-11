<?php

namespace Finance\OperationBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProcessImportDataType extends AbstractType
{
        
    /** ************************************************************************
     * @param FormBuilderInterface $builder
     * @param array $options
     **************************************************************************/
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('account', 'entity', array(
                'class'         => "FinanceAccountBundle:Account",
                'mapped'        => false,
                'required'      => true,
                'query_builder' => function(\Finance\AccountBundle\Entity\AccountRepository $r) {
                        return $r->createQueryBuilder('a')
                                ->leftjoin('a.institution','i')
                                ->leftjoin('a.category','c')
                                ->where('c INSTANCE OF FinanceAccountBundle:Ccp')
                                ->andWhere('i.name = :boursorama')
                                ->setParameter(':boursorama','Boursorama')
                                ;}
            ))
            ->add('data', 'textarea', array(
                'required'  => true,
                'mapped'        => false,                
            ))            
            ;
    }
    
    /** ************************************************************************
     * @param OptionsResolver $resolver
     **************************************************************************/
    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults(array(
            'allow_extra_fields' => true,
        ));
    }
    
    
    
    /** ************************************************************************
     * @return string
     **************************************************************************/
    public function getName() {
        return 'finance_operationbundle_processimportdata';
    }
}
