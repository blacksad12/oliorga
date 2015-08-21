<?php

namespace Nutri\RecipeBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ShoplistType extends AbstractType
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
            ->add('ingredientsForShoplist', 'collection', array(
                'type'          => new IngredientForShoplistType(),
                'mapped'        => false,
                'label'         => "Ingrédients",
                'allow_add'     => true,
                'allow_delete'  => true,
                'prototype'     => true,
            ))
            ->add('recipesForShoplist', 'collection', array(
                'type'          => new RecipeForShoplistType(),
                'mapped'        => false,
                'label'         => "Recipes",
                'allow_add'     => true,
                'allow_delete'  => true,
                'prototype'     => true,
            ))
            ;
    }
    
    /** ************************************************************************
     * @param OptionsResolver $resolver
     **************************************************************************/
    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults(array(
            'data_class' => 'Nutri\RecipeBundle\Entity\Shoplist'
        ));
    }

    /** ************************************************************************
     * @return string
     **************************************************************************/
    public function getName() {
        return 'nutri_recipebundle_shoplist';
    }
}
