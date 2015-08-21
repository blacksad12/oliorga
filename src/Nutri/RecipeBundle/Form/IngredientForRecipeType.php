<?php

namespace Nutri\RecipeBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class IngredientForRecipeType extends AbstractType
{
        
    /** ************************************************************************
     * @param FormBuilderInterface $builder
     * @param array $options
     **************************************************************************/
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            
            ->add('ingredient', 'entity', array(
                'class'     => 'NutriIngredientBundle:Ingredient',
                'multiple'  => false,
                'required'  => true,
            ))            
            ->add('quantity', 'number', array(
                'required'  => true,
                'grouping'  => true,
            ))            
            ->add('unit', 'choice', array(
                'expanded'  => false,
                'required'  => true,
                'choices'   => array(
                    'u' => 'Unité(s)',
                    'g' => 'Grammes',
                    'l' => 'Litres'
                ),
            ))
            ->add('comment', 'text', array(
                'required'  => false,
            ))            
            ;
    }
    
    /** ************************************************************************
     * @param OptionsResolver $resolver
     **************************************************************************/
    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults(array(
            'data_class' => 'Nutri\RecipeBundle\Entity\IngredientForRecipe'
        ));
    }

    /** ************************************************************************
     * @return string
     **************************************************************************/
    public function getName() {
        return 'nutri_recipebundle_ingredientforrecipe';
    }
}
