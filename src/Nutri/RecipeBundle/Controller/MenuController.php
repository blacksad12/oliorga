<?php

namespace Nutri\RecipeBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Nutri\RecipeBundle\Entity\Menu;
use Nutri\RecipeBundle\Form\MenuType;

/**
 * Menu controller.
 *
 * @Route("/menu")
 */
class MenuController extends Controller
{
    /** ************************************************************************
     * Display the Menu's homepage.
     * @Route("/")
     **************************************************************************/
    public function homeAction()        
    {
        $menus = $this->getDoctrine()
                ->getRepository('NutriRecipeBundle:Menu')
                ->findAll();
        
        return $this->render('NutriRecipeBundle:Menu:home.html.twig', array(
            'menus' => $menus
        ));
    }

    /** ************************************************************************
     * Create a new Menu according to the information given in the form.
     * @Route("/add")
     **************************************************************************/
    public function addAction()        
    {
        $menu = new Menu();
        
        $form = $this->createForm(new MenuType(), $menu);

        // ------------- Request Management ------------------------------------
        $request = $this->get('request');
        if ($request->getMethod() == 'POST') {
          $form->bind($request);// Link Request and Form

          if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            foreach($form['ingredientsForMenu']->getData() as $ingredientForMenu){
                $ingredientForMenu->setMenu($menu);
                $em->persist($ingredientForMenu);
            }
            $em->persist($menu);
            $em->flush();

            return $this->redirect($this->generateUrl('nutri_recipe_menu_see', array('menu_id' => $menu->getId())));
          }
        }

        return $this->render('NutriRecipeBundle:Menu:add.html.twig', array(
            'form' => $form->createView(),
        ));
    }
    
    /** ************************************************************************
     * Display a Menu
     * @param Menu $menu
     * @ParamConverter("menu", options={"mapping": {"menu_id": "id"}})
     * @Route("/see/{menu_id}", requirements={"menu_id" = "\d+"})
     **************************************************************************/
    public function seeAction(Menu $menu)
    {
        $ingredientListWithQuantities = $this->get('nutrirecipe.menuhelper')->getIngredientListWithQuantities($menu);
        $ingredientsIntakeArray = $this->get('nutriingredient.ingredienthelper')->getIntakeQuantityForIngredients($ingredientListWithQuantities);
        $totalIntakeArray = $this->get('nutrirecipe.menuhelper')->getTotalIntakeQuantityAndPercentage($menu);
        return $this->render('NutriRecipeBundle:Menu:see.html.twig', array(
            'menu'                      => $menu,
            'ingredientsIntakeArray'    => $ingredientsIntakeArray,
            'totalIntakeArray'          => $totalIntakeArray,
          ));
    }
    
    /** ************************************************************************
     * Modify a Menu according to the information given in the form.
     * 
     * @param Menu $menu
     * @ParamConverter("menu", options={"mapping": {"menu_id": "id"}})
     * @Route("/modify/{menu_id}", requirements={"menu_id" = "\d+"})
     **************************************************************************/
    public function modifyAction(Menu $menu)
    {
        $form = $this->createForm(new MenuType($menu), $menu);

        $existingIngredientsForMenu = $menu->getIngredientsForMenu()->toArray();
        $form['ingredientsForMenu']->setData($existingIngredientsForMenu);
        
        // ------------- Request Management ------------------------------------
        $request = $this->get('request');
        if ($request->getMethod() == 'POST') {
          $form->bind($request); // Link Request and Form

          if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            foreach ($existingIngredientsForMenu as $existingIngredientForMenu){
                if(!in_array($existingIngredientForMenu, $form['ingredientsForMenu']->getData())){
                    $em->remove($existingIngredientForMenu);
                }
            }
            $em->flush();
            
            foreach($form['ingredientsForMenu']->getData() as $ingredientForMenu){
                $ingredientForMenu->setMenu($menu);
                $em->persist($ingredientForMenu);
            }
            $em->persist($menu);
            $em->flush();

            return $this->redirect($this->generateUrl('nutri_recipe_menu_see', array('menu_id' => $menu->getId())));
          }
        }

        return $this->render('NutriRecipeBundle:Menu:modify.html.twig', array(
            'menu' => $menu,
            'form' => $form->createView(),           
        ));
    }
    
    /** ************************************************************************
     * Delete a Menu.
     * 
     * @param Menu $menu
     * @ParamConverter("menu", options={"mapping": {"menu_id": "id"}})
     * @Route("/delete/{menu_id}", requirements={"menu_id" = "\d+"})
     **************************************************************************/
    public function deleteAction(Menu $menu)
    {
        $request = $this->get('request');
        if ($request->getMethod() == 'POST') {
            $em = $this->getDoctrine()->getManager();
            $em->remove($menu);
            $em->flush();
            return $this->redirect($this->generateUrl('nutri_recipe_menu_home'));          
        }
        else{
            throw new \Symfony\Component\Security\Core\Exception\AccessDeniedException;
        }
    }
    
}
