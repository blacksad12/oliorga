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
            ->add('ingredient', 'shtumi_ajax_autocomplete', array(
                'entity_alias'  => 'ingredients'
            ))            
            ->add('quantity', 'integer', array(
                'required'  => true,
            ))            
            ->add('unit', 'choice', array(
                'expanded'  => false,
                'required'  => true,
                'choices'   => array(
                    'g'  => 'Grammes',
                    'cl' => 'Centilitres'
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
