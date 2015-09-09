<?php

namespace Finance\OperationBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CategoryType extends AbstractType
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
            ->add('parent', 'entity', array(
                'class'         => "FinanceOperationBundle:Category",
                'required'      => false,
                'query_builder' => function(\Finance\OperationBundle\Entity\CategoryRepository $r) {
                        return $r->createQueryBuilder('p')
                                ->orderBy('p.name')
                                ;}
            ))
            ->add('hexColor', 'text', array(
                'required'  => false,
            ))
            ;
    }
    
    /** ************************************************************************
     * @param OptionsResolver $resolver
     **************************************************************************/
    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults(array(
            'data_class' => 'Finance\OperationBundle\Entity\Category'
        ));
    }

    /** ************************************************************************
     * @return string
     **************************************************************************/
    public function getName() {
        return 'finance_operationbundle_category';
    }
}
