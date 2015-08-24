<?php

namespace Nutri\RecipeBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Nutri\RecipeBundle\Entity\Recipe;
use Nutri\RecipeBundle\Form\RecipeType;

/**
 * Recipe controller.
 *
 * @Route("/recipe")
 */
class RecipeController extends Controller
{
    /** ************************************************************************
     * Display the Recipe's homepage.
     * @Route("/")
     **************************************************************************/
    public function homeAction()        
    {
        $recipes = $this->getDoctrine()
                ->getRepository('NutriRecipeBundle:Recipe')
                ->findAll();
        
        return $this->render('NutriRecipeBundle:Recipe:home.html.twig', array(
            'recipes' => $recipes
        ));
    }

    /** ************************************************************************
     * Create a new Recipe according to the information given in the form.
     * @Route("/add")
     **************************************************************************/
    public function addAction()        
    {
        $recipe = new Recipe();
        
        $form = $this->createForm(new RecipeType(), $recipe);

        // ------------- Request Management ------------------------------------
        $request = $this->get('request');
        if ($request->getMethod() == 'POST') {
          $form->bind($request);// Link Request and Form

          if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            foreach($form['ingredientsForRecipe']->getData() as $ingredientForRecipe){
                $ingredientForRecipe->setRecipe($recipe);
                $em->persist($ingredientForRecipe);
            }
            $em->persist($recipe);
            $em->flush();

            return $this->redirect($this->generateUrl('nutri_recipe_recipe_see', array('recipe_id' => $recipe->getId())));
          }
        }

        return $this->render('NutriRecipeBundle:Recipe:add.html.twig', array(
            'form' => $form->createView(),
        ));
    }
    
    /** ************************************************************************
     * Display a Recipe
     * @param Recipe $recipe
     * @ParamConverter("recipe", options={"mapping": {"recipe_id": "id"}})
     * @Route("/see/{recipe_id}", requirements={"recipe_id" = "\d+"})
     **************************************************************************/
    public function seeAction(Recipe $recipe)
    {
        $referenceDailyIntakePercentage = $this->get('nutrirecipe.recipehelper')->getReferenceDailyIntakePercentage($recipe);
        
        return $this->render('NutriRecipeBundle:Recipe:see.html.twig', array(
            'recipe'      => $recipe,
            'referenceDailyIntakePercentage' => $referenceDailyIntakePercentage,
          ));
    }
    
    /** ************************************************************************
     * Modify a Recipe according to the information given in the form.
     * 
     * @param Recipe $recipe
     * @ParamConverter("recipe", options={"mapping": {"recipe_id": "id"}})
     * @Route("/modify/{recipe_id}", requirements={"recipe_id" = "\d+"})
     **************************************************************************/
    public function modifyAction(Recipe $recipe)
    {
        $form = $this->createForm(new RecipeType($recipe), $recipe);

        $existingIngredientsForRecipe = $recipe->getIngredientsForRecipe()->toArray();
            
        $form['ingredientsForRecipe']->setData($existingIngredientsForRecipe);
        // ------------- Request Management ------------------------------------
        $request = $this->get('request');
        if ($request->getMethod() == 'POST') {
          $form->bind($request); // Link Request and Form

          if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            foreach ($existingIngredientsForRecipe as $existingIngredientForRecipe){
                if(!in_array($existingIngredientForRecipe, $form['ingredientsForRecipe']->getData())){
                    $em->remove($existingIngredientForRecipe);
                }
            }
            $em->flush();
            
            foreach($form['ingredientsForRecipe']->getData() as $ingredientForRecipe){
                $ingredientForRecipe->setRecipe($recipe);
                $em->persist($ingredientForRecipe);
            }
            $em->persist($recipe);
            $em->flush();

            return $this->redirect($this->generateUrl('nutri_recipe_recipe_see', array('recipe_id' => $recipe->getId())));
          }
        }

        return $this->render('NutriRecipeBundle:Recipe:modify.html.twig', array(
            'recipe' => $recipe,
            'form' => $form->createView(),           
        ));
    }
    
    /** ************************************************************************
     * Delete a Recipe.
     * 
     * @param Recipe $recipe
     * @ParamConverter("recipe", options={"mapping": {"recipe_id": "id"}})
     * @Route("/delete/{recipe_id}", requirements={"recipe_id" = "\d+"})
     **************************************************************************/
    public function deleteAction(Recipe $recipe)
    {
        $request = $this->get('request');
        if ($request->getMethod() == 'POST') {
            $em = $this->getDoctrine()->getManager();
            $em->remove($recipe);
            $em->flush();
            return $this->redirect($this->generateUrl(/* Redirect to some page */));          
        }
        else{
            throw new \Symfony\Component\Security\Core\Exception\AccessDeniedException;
        }
    }
    
}
