<?php

namespace Finance\AccountBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AccountType extends AbstractType
{
        
    /** ************************************************************************
     * @param FormBuilderInterface $builder
     * @param array $options
     **************************************************************************/
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            
            ->add('number', 'text', array(
                'required'  => false,
            ))            
            ->add('iban', 'text', array(
                'required'  => false,
            ))            
            ->add('openDate', 'date', array(
                'required'  => false,
                'widget'    => 'single_text',
                'input'     => 'datetime',
                'format'    => 'dd/MM/yyyy',
                'attr'      => array('class' => 'dateonly'),
            ))            
            ->add('closeDate', 'date', array(
                'required'  => false,
                'widget'    => 'single_text',
                'input'     => 'datetime',
                'format'    => 'dd/MM/yyyy',
                'attr'      => array('class' => 'dateonly'),
            ))                    
            ->add('category', 'entity', array(
                'class'         => "FinanceAccountBundle:Category",
                'required'      => true,
                'query_builder' => function(\Finance\AccountBundle\Entity\CategoryRepository $r) {
                        return $r->createQueryBuilder('c')
                                ;}
            ))                    
            ->add('institution', 'entity', array(
                'class'         => "FinanceInstitutionBundle:Institution",
                'required'      => true,
                'query_builder' => function(\Finance\InstitutionBundle\Entity\InstitutionRepository $r) {
                        return $r->createQueryBuilder('i')
                                ;}
            ))        ;
    }
    
    /** ************************************************************************
     * @param OptionsResolver $resolver
     **************************************************************************/
    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults(array(
            'data_class' => 'Finance\AccountBundle\Entity\Account'
        ));
    }

    /** ************************************************************************
     * @return string
     **************************************************************************/
    public function getName() {
        return 'finance_accountbundle_account';
    }
}
