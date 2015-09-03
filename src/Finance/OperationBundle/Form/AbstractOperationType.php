<?php

namespace Finance\OperationBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AbstractOperationType extends AbstractType
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
            ->add('amount', 'integer', array(
                'required'  => true,
            ))            
            ->add('isMarked', 'choice', array(
                'required'  => false,
                'choices'   => array(
                    false   => 'No',
                    true    => 'Yes',
                ),
                'multiple'  => false,
                'expanded'  => true,
                'widget_type'  => 'inline',
            ))        ;
    }
    
    /** ************************************************************************
     * @param OptionsResolver $resolver
     **************************************************************************/
    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults(array(
            'data_class' => 'Finance\OperationBundle\Entity\AbstractOperation'
        ));
    }

    /** ************************************************************************
     * @return string
     **************************************************************************/
    public function getName() {
        return 'finance_operationbundle_abstractoperation';
    }
}
