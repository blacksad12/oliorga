<?php

namespace Nutri\RecipeBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class IngredientForShoplistType extends AbstractType
{
        
    /** ************************************************************************
     * @param FormBuilderInterface $builder
     * @param array $options
     **************************************************************************/
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('ingredient', 'entity', array(
                'class'         => "NutriIngredientBundle:Ingredient",
                'required'      => true,
                'query_builder' => function(\Nutri\IngredientBundle\Entity\IngredientRepository $r) {
                        return $r->createQueryBuilder('i')
                                ;}
            ))                    
            ->add('quantity', 'integer', array(
                'required'  => true,
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
            ;
    }
    
    /** ************************************************************************
     * @param OptionsResolver $resolver
     **************************************************************************/
    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults(array(
            'data_class' => 'Nutri\RecipeBundle\Entity\IngredientForShoplist'
        ));
    }

    /** ************************************************************************
     * @return string
     **************************************************************************/
    public function getName() {
        return 'nutri_recipebundle_ingredientforshoplist';
    }
}
