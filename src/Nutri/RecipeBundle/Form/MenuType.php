<?php

namespace Nutri\RecipeBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MenuType extends AbstractType
{
        
    /** ************************************************************************
     * @param FormBuilderInterface $builder
     * @param array $options
     **************************************************************************/
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            
            ->add('date', 'date', array(
                'required'  => false,
                'widget'    => 'single_text',
                'input'     => 'datetime',
                'format'    => 'dd/MM/yyyy',
                'attr'      => array('class' => 'dateonly'),
            ))            
            ->add('person', 'entity', array(
                'class'         => "NutriRecipeBundle:Person",
                'required'      => true,
            ))                    
            ->add('ingredientsForMenu', 'collection', array(
                'type'          => new IngredientForMenuType(),
                'mapped'        => false,
                'label'         => "Ingrédients",
                'allow_add'     => true,
                'allow_delete'  => true,
                'prototype'     => true,
            ))
            ->add('recipes', 'entity', array(
                'class'         => "NutriRecipeBundle:Recipe",
                'required'      => false,
                'multiple'      => true,
                'query_builder' => function(\Nutri\RecipeBundle\Entity\RecipeRepository $r) {
                        return $r->createQueryBuilder('r')
                                ;}
            ))        ;
    }
    
    /** ************************************************************************
     * @param OptionsResolver $resolver
     **************************************************************************/
    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults(array(
            'data_class' => 'Nutri\RecipeBundle\Entity\Menu'
        ));
    }

    /** ************************************************************************
     * @return string
     **************************************************************************/
    public function getName() {
        return 'nutri_recipebundle_menu';
    }
}
