<?php

namespace Nutri\IngredientBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class IngredientType extends AbstractType
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
            ->add('barcode', 'number', array(
                'required'  => false,
            ))            
            ->add('ciqualcode', 'number', array(
                'required'  => false,
            ))            
            ->add('energyKcal', 'number', array(
                'required'  => false,
            ))            
            ->add('fat', 'number', array(
                'required'  => false,
            ))            
            ->add('saturatedFat', 'number', array(
                'required'  => false,
            ))            
            ->add('carbohydrate', 'number', array(
                'required'  => false,
            ))            
            ->add('sugars', 'number', array(
                'required'  => false,
            ))            
            ->add('fiber', 'number', array(
                'required'  => false,
            ))            
            ->add('proteins', 'number', array(
                'required'  => false,
            ))            
            ->add('salt', 'number', array(
                'required'  => false,
            ))            
            ->add('sodium', 'number', array(
                'required'  => false,
            ))        ;
    }
    
    /** ************************************************************************
     * @param OptionsResolver $resolver
     **************************************************************************/
    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults(array(
            'data_class' => 'Nutri\IngredientBundle\Entity\Ingredient'
        ));
    }

    /** ************************************************************************
     * @return string
     **************************************************************************/
    public function getName() {
        return 'nutri_ingredientbundle_ingredient';
    }
}
