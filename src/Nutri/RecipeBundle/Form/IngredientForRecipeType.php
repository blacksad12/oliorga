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
    private $entityManager;

    public function __construct(\Doctrine\ORM\EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    
    /** ************************************************************************
     * @param FormBuilderInterface $builder
     * @param array $options
     **************************************************************************/
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $repository = $this->entityManager->getRepository('NutriIngredientBundle:Ingredient');
        $builder
            ->add('ingredient', 'shtumi_ajax_autocomplete', array(
                'entity_alias'  => 'ingredients'
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
            
            //$this->setFormModifierForIngredient($builder);
    }
    
    /** ************************************************************************
     * Set up the form modifyer 
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     **************************************************************************/
    protected function setFormModifierForIngredient(FormBuilderInterface $builder)
    {
        $formModifier = function (FormInterface $form, \Nutri\IngredientBundle\Entity\Ingredient $ingredient) {
            $form->add('ingredient', 'ingredient_selector', array(
                    'multiple'  => false,
                    'required'  => true,
                    'choices'   => array(
                        $ingredient->getId() => $ingredient->getName(),
                        '2' => 'test',
                    ),
                    ));
            //dump($form);exit();
        };
        
//        $builder->addEventListener(
//            FormEvents::PRE_SET_DATA,
//            function (FormEvent $formEvent) use ($formModifier) {
//                if (null != $formEvent->getData()) {
//                    $ingredientForRecipe = $formEvent->getData();
//                    dump($formEvent->getData());exit;
//                    $formModifier($formEvent->getForm(), $ingredientForRecipe);
//                }
//            }
//        );
                
        $builder->get('ingredient')->addEventListener(
            FormEvents::POST_SUBMIT,
            function (FormEvent $formEvent) use ($formModifier) {
                //if (null != $formEvent->getForm()->getParent()->getData()) {
                    dump($formEvent);
                    dump($formEvent);exit;
//                }
//                else{
//                    dump('formevent is null');
//                }
                return;
                $ingredient = $formEvent->getForm()->getData();
                $formModifier($formEvent->getForm()->getParent(), $ingredient);
                return;
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
