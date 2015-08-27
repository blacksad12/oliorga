<?php

namespace Nutri\RecipeBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Nutri\RecipeBundle\Entity\Shoplist;
use Nutri\RecipeBundle\Form\ShoplistType;

/**
 * Shoplist controller.
 *
 * @Route("/shoplist")
 */
class ShoplistController extends Controller
{
    /** ************************************************************************
     * Display the Shoplist's homepage.
     * @Route("/")
     **************************************************************************/
    public function homeAction()        
    {
        $shoplists = $this->getDoctrine()
                ->getRepository('NutriRecipeBundle:Shoplist')
                ->findAll();
        
        return $this->render('NutriRecipeBundle:Shoplist:home.html.twig', array(
            'shoplists' => $shoplists
        ));
    }

    /** ************************************************************************
     * Create a new Shoplist according to the information given in the form.
     * @Route("/add")
     **************************************************************************/
    public function addAction()        
    {
        $shoplist = new Shoplist();
        
        $form = $this->createForm(new ShoplistType(), $shoplist);

        // ------------- Request Management ------------------------------------
        $request = $this->get('request');
        if ($request->getMethod() == 'POST') {
          $form->bind($request);// Link Request and Form

          if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            foreach($form['ingredientsForShoplist']->getData() as $ingredientForShoplist){
                $ingredientForShoplist->setShoplist($shoplist);
                $em->persist($ingredientForShoplist);
            }
            foreach($form['recipesForShoplist']->getData() as $recipeForShoplist){
                $recipeForShoplist->setShoplist($shoplist);
                $em->persist($recipeForShoplist);
            }
            $em->persist($shoplist);
            $em->flush();

            return $this->redirect($this->generateUrl('nutri_recipe_shoplist_see', array('shoplist_id' => $shoplist->getId())));
          }
        }

        return $this->render('NutriRecipeBundle:Shoplist:add.html.twig', array(
            'form' => $form->createView(),
        ));
    }
    
    /** ************************************************************************
     * Display a Shoplist
     * @param Shoplist $shoplist
     * @ParamConverter("shoplist", options={"mapping": {"shoplist_id": "id"}})
     * @Route("/see/{shoplist_id}", requirements={"shoplist_id" = "\d+"})
     **************************************************************************/
    public function seeAction(Shoplist $shoplist)
    {
        $ingredientList = $this->get('nutrirecipe.shoplisthelper')->getIngredientListWithQuantities($shoplist);
        
        return $this->render('NutriRecipeBundle:Shoplist:see.html.twig', array(
            'shoplist'      => $shoplist, 
            'ingredientList'    => $ingredientList,
          ));
    }
    
    /** ************************************************************************
     * Modify a Shoplist according to the information given in the form.
     * 
     * @param Shoplist $shoplist
     * @ParamConverter("shoplist", options={"mapping": {"shoplist_id": "id"}})
     * @Route("/modify/{shoplist_id}", requirements={"shoplist_id" = "\d+"})
     **************************************************************************/
    public function modifyAction(Shoplist $shoplist)
    {
        $form = $this->createForm(new ShoplistType($shoplist), $shoplist);

        $existingIngredientsForShoplist = $shoplist->getIngredientsForShoplist()->toArray();
        $existingRecipesForShoplist = $shoplist->getRecipesForShoplist()->toArray();
            
        $form['ingredientsForShoplist']->setData($existingIngredientsForShoplist);
        $form['recipesForShoplist']->setData($existingRecipesForShoplist);
        
        // ------------- Request Management ------------------------------------
        $request = $this->get('request');
        if ($request->getMethod() == 'POST') {
          $form->bind($request); // Link Request and Form

          if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            foreach ($existingIngredientsForShoplist as $existingIngredientForShoplist){
                if(!in_array($existingIngredientForShoplist, $form['ingredientsForShoplist']->getData())){
                    $em->remove($existingIngredientForShoplist);
                }
            }
            foreach ($existingRecipesForShoplist as $existingRecipeForShoplist){
                if(!in_array($existingRecipeForShoplist, $form['recipesForShoplist']->getData())){
                    $em->remove($existingRecipeForShoplist);
                }
            }
            $em->flush();
            
            foreach($form['ingredientsForShoplist']->getData() as $ingredientForShoplist){
                $ingredientForShoplist->setShoplist($shoplist);
                $em->persist($ingredientForShoplist);
            }
            foreach($form['recipesForShoplist']->getData() as $recipeForShoplist){
                $recipeForShoplist->setShoplist($shoplist);
                $em->persist($recipeForShoplist);
            }
            $em->persist($shoplist);
            $em->flush();

            return $this->redirect($this->generateUrl('nutri_recipe_shoplist_see', array('shoplist_id' => $shoplist->getId())));
          }
        }

        return $this->render('NutriRecipeBundle:Shoplist:modify.html.twig', array(
            'shoplist' => $shoplist,
            'form' => $form->createView(),           
        ));
    }
    
    /** ************************************************************************
     * Delete a Shoplist.
     * 
     * @param Shoplist $shoplist
     * @ParamConverter("shoplist", options={"mapping": {"shoplist_id": "id"}})
     * @Route("/delete/{shoplist_id}", requirements={"shoplist_id" = "\d+"})
     **************************************************************************/
    public function deleteAction(Shoplist $shoplist)
    {
        $request = $this->get('request');
        if ($request->getMethod() == 'POST') {
            $em = $this->getDoctrine()->getManager();
            $em->remove($shoplist);
            $em->flush();
            return $this->redirect($this->generateUrl('nutri_recipe_shoplist_home'));          
        }
        else{
            throw new \Symfony\Component\Security\Core\Exception\AccessDeniedException;
        }
    }
    
}
