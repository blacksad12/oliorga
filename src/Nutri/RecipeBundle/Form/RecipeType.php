<?php

namespace Nutri\RecipeBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

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
            ))          
            ->add('detail', 'textarea', array(
                'required'  => false,
            ))        ;
        $builder->addEventListener(FormEvents::POST_SUBMIT, function (FormEvent $event) {
            $event->stopPropagation();
        }, 900); // Always set a higher priority than ValidationListener

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
