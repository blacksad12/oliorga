<?php

namespace Finance\OperationBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Finance\OperationBundle\Entity\Category;
use Finance\OperationBundle\Form\CategoryType;

/**
 * Category controller.
 *
 * @Route("/category")
 */
class CategoryController extends Controller
{
    /** ************************************************************************
     * Display the Category's homepage.
     * @Route("/")
     **************************************************************************/
    public function homeAction()        
    {
        $categorys = $this->getDoctrine()
                ->getRepository('FinanceOperationBundle:Category')
                ->findAll();
        
        return $this->render('FinanceOperationBundle:Category:home.html.twig', array(
            'categorys' => $categorys
        ));
    }

    /** ************************************************************************
     * Create a new Category according to the information given in the form.
     * @Route("/add")
     **************************************************************************/
    public function addAction()        
    {
        $category = new Category();
        
        $form = $this->createForm(new CategoryType(), $category);

        // ------------- Request Management ------------------------------------
        $request = $this->get('request');
        if ($request->getMethod() == 'POST') {
          $form->bind($request);// Link Request and Form

          if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($category);
            $em->flush();

            return $this->redirect($this->generateUrl('finance_operation_category_see', array('category_id' => $category->getId())));
          }
        }

        return $this->render('FinanceOperationBundle:Category:add.html.twig', array(
            'form' => $form->createView(),
        ));
    }
    
    /** ************************************************************************
     * Display a Category
     * @param Category $category
     * @ParamConverter("category", options={"mapping": {"category_id": "id"}})
     * @Route("/see/{category_id}", requirements={"category_id" = "\d+"})
     **************************************************************************/
    public function seeAction(Category $category)
    {
        $operations = $this->getDoctrine()->getRepository('FinanceOperationBundle:Operation')->getOperations(array('category' => $category));
        $balanceHistoryPerMonth = $this->get('financeoperation.categoryhelper')->getBalanceHistoryPerMonth(array('category' => $category));
        $monthlyMeans = $this->get('financeoperation.categoryhelper')->getMonthlyMeans(array('category' => $category));
        dump($monthlyMeans);
        return $this->render('FinanceOperationBundle:Category:see.html.twig', array(
            'category'      => $category,
            'operations'    => $operations,
            'monthlyMeans'  => $monthlyMeans,
          ));
    }
    
    /** ************************************************************************
     * Modify a Category according to the information given in the form.
     * 
     * @param Category $category
     * @ParamConverter("category", options={"mapping": {"category_id": "id"}})
     * @Route("/modify/{category_id}", requirements={"category_id" = "\d+"})
     **************************************************************************/
    public function modifyAction(Category $category)
    {
        $form = $this->createForm(new CategoryType($category), $category);

        // ------------- Request Management ------------------------------------
        $request = $this->get('request');
        if ($request->getMethod() == 'POST') {
          $form->bind($request); // Link Request and Form

          if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($category);
            $em->flush();

            return $this->redirect($this->generateUrl('finance_operation_category_see', array('category_id' => $category->getId())));
          }
        }

        return $this->render('FinanceOperationBundle:Category:modify.html.twig', array(
            'category' => $category,
            'form' => $form->createView(),           
        ));
    }
    
    /** ************************************************************************
     * Delete a Category.
     * 
     * @param Category $category
     * @ParamConverter("category", options={"mapping": {"category_id": "id"}})
     * @Route("/delete/{category_id}", requirements={"category_id" = "\d+"})
     **************************************************************************/
    public function deleteAction(Category $category)
    {
        $request = $this->get('request');
        if ($request->getMethod() == 'POST') {
            $em = $this->getDoctrine()->getManager();
            $em->remove($category);
            $em->flush();
            return $this->redirect($this->generateUrl(/* Redirect to some page */));          
        }
        else{
            throw new \Symfony\Component\Security\Core\Exception\AccessDeniedException;
        }
    }
    
}
