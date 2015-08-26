<?php

namespace Nutri\RecipeBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;

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
                'query_builder' => function(\Nutri\IngredientBundle\Entity\IngredientRepository $r) {
                    return $r->createQueryBuilder('r')
                           ->where('r.id is NULL')
                            ;}
            ))            
            ->add('quantity', 'number', array(
                'required'  => true,
                'grouping'  => true,
            ))            
            ->add('unit', 'choice', array(
                'expanded'  => false,
                'required'  => true,
                'choices'   => array(
                    'g'  => 'Grammes',
                    'cl' => 'Centilitres'
                ),
            ))
            ->add('comment', 'text', array(
                'required'  => false,
            ))            
            ;        
            
            $this->setFormModifierForIngredient($builder);
    }
    
    /** ************************************************************************
     * Set up the form modifyer 
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     **************************************************************************/
    protected function setFormModifierForIngredient(FormBuilderInterface $builder)
    {
        $formModifier = function (FormInterface $form, \Nutri\RecipeBundle\Entity\IngredientForRecipe $ingredientForRecipe = null) {
            $form->add('ingredient', 'entity', array(
                    'class'     => 'NutriIngredientBundle:Ingredient',
                    'multiple'  => false,
                    'required'  => true,
                    'query_builder' => function(\Nutri\IngredientBundle\Entity\IngredientRepository $r) use ($ingredientForRecipe){
                        if($ingredientForRecipe === NULL) {
                            return $r->createQueryBuilder('r')->where('r.id is NULL');
                        }
                        else {
                            return $r->find($ingredientForRecipe->getIngredient()->getId());
                        }                        
                        }
                    ));
        };
        
        $builder->addEventListener(
            FormEvents::PRE_SET_DATA,
            function (FormEvent $formEvent) use ($formModifier) {
                $ingredientForRecipe = $formEvent->getData();
                $formModifier($formEvent->getForm(), $ingredientForRecipe);
            }
        );
                
        $builder->get('ingredient')->addEventListener(
            FormEvents::POST_SUBMIT,
            function (FormEvent $formEvent) use ($formModifier) {
                $ingredientForRecipe = $formEvent->getForm()->getParent()->getData();                
                $ingredientForRecipe->setIngredient($formEvent->getForm()->getData());
                $formModifier($formEvent->getForm()->getParent(), $ingredientForRecipe);
            }
        );        
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
