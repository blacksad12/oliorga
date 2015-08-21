<?php

namespace Nutri\RecipeBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RecipeForShoplistType extends AbstractType
{
        
    /** ************************************************************************
     * @param FormBuilderInterface $builder
     * @param array $options
     **************************************************************************/
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('recipe', 'entity', array(
                'class'         => "NutriRecipeBundle:Recipe",
                'required'      => true,
                'query_builder' => function(\Nutri\RecipeBundle\Entity\RecipeRepository $r) {
                        return $r->createQueryBuilder('r')
                                ;}
            ))            
            ->add('nbPeople', 'integer', array(
                'required'  => true,
            ))                    
            ;
    }
    
    /** ************************************************************************
     * @param OptionsResolver $resolver
     **************************************************************************/
    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults(array(
            'data_class' => 'Nutri\RecipeBundle\Entity\RecipeForShoplist'
        ));
    }

    /** ************************************************************************
     * @return string
     **************************************************************************/
    public function getName() {
        return 'nutri_recipebundle_recipeforshoplist';
    }
}
