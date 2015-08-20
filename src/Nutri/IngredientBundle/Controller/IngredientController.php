<?php

namespace Nutri\IngredientBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Nutri\IngredientBundle\Entity\Ingredient;
use Nutri\IngredientBundle\Form\IngredientType;

/**
 * Ingredient controller.
 *
 * @Route("/ingredient")
 */
class IngredientController extends Controller
{
    /** ************************************************************************
     * Display the Ingredient's homepage.
     * @Route("/")
     **************************************************************************/
    public function homeAction()        
    {
        $ingredients = $this->getDoctrine()
                ->getRepository('NutriIngredientBundle:Ingredient')
                ->findAll();
        //$this->get('nutriingredient.ciqualingredientimporter')->import();
        return $this->render('NutriIngredientBundle:Ingredient:home.html.twig', array(
            'ingredients' => $ingredients
        ));
    }

    /** ************************************************************************
     * Create a new Ingredient according to the information given in the form.
     * @Route("/add")
     **************************************************************************/
    public function addAction()        
    {
        $ingredient = new Ingredient();
        
        $form = $this->createForm(new IngredientType(), $ingredient);

        // ------------- Request Management ------------------------------------
        $request = $this->get('request');
        if ($request->getMethod() == 'POST') {
          $form->bind($request);// Link Request and Form

          if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($ingredient);
            $em->flush();

            return $this->redirect($this->generateUrl('nutri_ingredient_ingredient_see', array('ingredient_id' => $ingredient->getId())));
          }
        }

        return $this->render('NutriIngredientBundle:Ingredient:add.html.twig', array(
            'form' => $form->createView(),
        ));
    }
    
    /** ************************************************************************
     * Display a Ingredient
     * @param Ingredient $ingredient
     * @ParamConverter("ingredient", options={"mapping": {"ingredient_id": "id"}})
     * @Route("/see/{ingredient_id}", requirements={"ingredient_id" = "\d+"})
     **************************************************************************/
    public function seeAction(Ingredient $ingredient)
    {
        
        return $this->render('NutriIngredientBundle:Ingredient:see.html.twig', array(
            'ingredient'      => $ingredient,            
          ));
    }
    
    /** ************************************************************************
     * Modify a Ingredient according to the information given in the form.
     * 
     * @param Ingredient $ingredient
     * @ParamConverter("ingredient", options={"mapping": {"ingredient_id": "id"}})
     * @Route("/modify/{ingredient_id}", requirements={"ingredient_id" = "\d+"})
     **************************************************************************/
    public function modifyAction(Ingredient $ingredient)
    {
        $form = $this->createForm(new IngredientType($ingredient), $ingredient);

        // ------------- Request Management ------------------------------------
        $request = $this->get('request');
        if ($request->getMethod() == 'POST') {
          $form->bind($request); // Link Request and Form

          if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($ingredient);
            $em->flush();

            return $this->redirect($this->generateUrl('nutri_ingredient_ingredient_see', array('ingredient_id' => $ingredient->getId())));
          }
        }

        return $this->render('NutriIngredientBundle:Ingredient:modify.html.twig', array(
            'ingredient' => $ingredient,
            'form' => $form->createView(),           
        ));
    }
    
    /** ************************************************************************
     * Delete a Ingredient.
     * 
     * @param Ingredient $ingredient
     * @ParamConverter("ingredient", options={"mapping": {"ingredient_id": "id"}})
     * @Route("/delete/{ingredient_id}", requirements={"ingredient_id" = "\d+"})
     **************************************************************************/
    public function deleteAction(Ingredient $ingredient)
    {
        $request = $this->get('request');
        if ($request->getMethod() == 'POST') {
            $em = $this->getDoctrine()->getManager();
            $em->remove($ingredient);
            $em->flush();
            return $this->redirect($this->generateUrl(/* Redirect to some page */));          
        }
        else{
            throw new \Symfony\Component\Security\Core\Exception\AccessDeniedException;
        }
    }
    
}
