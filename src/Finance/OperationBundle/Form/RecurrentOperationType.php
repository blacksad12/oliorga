<?php

namespace Finance\OperationBundle\Form;

use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RecurrentOperationType extends OperationType
{
        
    /** ************************************************************************
     * @param FormBuilderInterface $builder
     * @param array $options
     **************************************************************************/
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('recurrenceDay', 'number', array(
                'required'  => true,
                'label'     => 'Recurrence Day',
            ));
        parent::buildForm($builder, $options);
        $builder->remove('date');
        $builder->remove('isMarked');
        
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
        return 'finance_operationbundle_recurrentoperation';
    }
}
