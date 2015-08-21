<?php

namespace Nutri\RecipeBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RecipeType extends AbstractType
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
            ->add('nbPeople', 'number', array(
                'required'  => false,
            ))            
            ->add('preparationTime', 'number', array(
                'required'  => false,
            ))            
            ->add('cookingTime', 'number', array(
                'required'  => false,
            ))            
            ->add('cookingTemperature', 'number', array(
                'required'  => false,
            ))            
            ->add('ingredientsForRecipe', 'collection', array(
                'type'          => new IngredientForRecipeType(),
                'mapped'        => false,
                'label'         => "Ingrédients",
                'allow_add'     => true,
                'allow_delete'  => true,
                'prototype'     => true,
                //'widget_add_btn' => false,
//                'options'       => array( // options for collection fields
//                    'label_render'      => false,
//                ),                
            ))          
            ->add('detail', 'textarea', array(
                'required'  => false,
            ))        ;
    }
    
    /** ************************************************************************
     * @param OptionsResolver $resolver
     **************************************************************************/
    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults(array(
            'data_class' => 'Nutri\RecipeBundle\Entity\Recipe'
        ));
    }

    /** ************************************************************************
     * @return string
     **************************************************************************/
    public function getName() {
        return 'nutri_recipebundle_recipe';
    }
}
