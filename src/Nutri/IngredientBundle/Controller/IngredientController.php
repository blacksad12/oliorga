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
     * @Route("/{currentPageNumber}", requirements={"currentPageNumber" = "\d+"}, defaults={"currentPageNumber" = 1})
     **************************************************************************/
    public function homeAction($currentPageNumber)        
    {
        $maxIngredients = 20;
        $ingredientsCount = $this->getDoctrine()
                ->getRepository('NutriIngredientBundle:Ingredient')
                ->countTotal();
        
        $pagination = array(
            'page' => $currentPageNumber,
            'route' => 'nutri_ingredient_ingredient_home',
            'pages_count' => ceil($ingredientsCount / $maxIngredients),
            'route_params' => array()
        );
 
         $ingredients = $this->getDoctrine()->getRepository('NutriIngredientBundle:Ingredient')
                ->getList($currentPageNumber, $maxIngredients);
 
        return $this->render('NutriIngredientBundle:Ingredient:home.html.twig', array(
            'ingredients' => $ingredients,
            'pagination' => $pagination
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
        
    /** ************************************************************************
     * Ajax function. 
     * 
     * @Route("/search")
     * @Method({"POST","GET"})
     **************************************************************************/
    public function searchAction()
    {        
        $name = $this->get('request')->request->get('name');
        $nameStringArray = explode(' ',$name);
        $searchInCiqual = ($this->get('request')->request->get('ciqual') === 'true');
        $searchInOpenFoodFact = ($this->get('request')->request->get('openfoodfact') === 'true');
        
        $em = $this->getDoctrine()->getManager();
        $qb = $em->createQueryBuilder();
        
        $qb->select('i');
        $qb->from("NutriIngredientBundle:Ingredient",'i');
        foreach($nameStringArray as $key=>$nameString) {
            $qb->andWhere($qb->expr()->like('i.name', ':nameSearched_'.$key));
            $qb->setParameter(':nameSearched_'.$key, '%'.$nameString.'%');
        }
        if(!$searchInCiqual) {
            $qb->andWhere($qb->expr()->isNull('i.ciqualcode'));
        }
        if(!$searchInOpenFoodFact) {
            $qb->andWhere($qb->expr()->isNull('i.barcode'));
        }
        $qb->orderBy('i.name');
        
        $ingredientArray = $qb->getQuery()->getResult(\Doctrine\ORM\Query::HYDRATE_ARRAY);
        return $this->render('NutriIngredientBundle:Ingredient:searchResultDisplay.html.twig', array(
            'ingredients' => $ingredientArray,
        )); 
    }
    
    
}
