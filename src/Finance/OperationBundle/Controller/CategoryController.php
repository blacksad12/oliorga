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
        $categories = $this->getDoctrine()
                ->getRepository('FinanceOperationBundle:Category')
                ->findTopParents();        
           
        return $this->render('FinanceOperationBundle:Category:home.html.twig', array(
            'categories' => $categories,
        ));
    }

    /** ************************************************************************
     * Display the Category's distribution.
     * @Route("/distribution")
     **************************************************************************/
    public function distributionAction()        
    {
        $categories = $this->getDoctrine()
                ->getRepository('FinanceOperationBundle:Category')
                ->findTopParents();        
        $monthlyMeansForCategories = array();
        foreach($categories as $key=>$category) {
            $monthlyMeansForCategories[$key] = $this->get('financeoperation.categoryhelper')->getMonthlyMeans(array('category' => $category));
        }
        $charts = array();
        $charts['one']['credit']        = $this->get('financeoperation.categoryhelper')->getAllCategoriesMonthlyMeansCharts($monthlyMeansForCategories, 'one', 'credit')->renderOptions();
        $charts['one']['debit']         = $this->get('financeoperation.categoryhelper')->getAllCategoriesMonthlyMeansCharts($monthlyMeansForCategories, 'one', 'debit')->renderOptions();
        $charts['three']['credit']      = $this->get('financeoperation.categoryhelper')->getAllCategoriesMonthlyMeansCharts($monthlyMeansForCategories, 'three', 'credit')->renderOptions();
        $charts['three']['debit']       = $this->get('financeoperation.categoryhelper')->getAllCategoriesMonthlyMeansCharts($monthlyMeansForCategories, 'three', 'debit')->renderOptions();
        $charts['six']['credit']        = $this->get('financeoperation.categoryhelper')->getAllCategoriesMonthlyMeansCharts($monthlyMeansForCategories, 'six', 'credit')->renderOptions();
        $charts['six']['debit']         = $this->get('financeoperation.categoryhelper')->getAllCategoriesMonthlyMeansCharts($monthlyMeansForCategories, 'six', 'debit')->renderOptions();
        $charts['thisYear']['credit']   = $this->get('financeoperation.categoryhelper')->getAllCategoriesMonthlyMeansCharts($monthlyMeansForCategories, 'thisYear', 'credit')->renderOptions();
        $charts['thisYear']['debit']    = $this->get('financeoperation.categoryhelper')->getAllCategoriesMonthlyMeansCharts($monthlyMeansForCategories, 'thisYear', 'debit')->renderOptions();
        $charts['lastYear']['credit']   = $this->get('financeoperation.categoryhelper')->getAllCategoriesMonthlyMeansCharts($monthlyMeansForCategories, 'lastYear', 'credit')->renderOptions();
        $charts['lastYear']['debit']    = $this->get('financeoperation.categoryhelper')->getAllCategoriesMonthlyMeansCharts($monthlyMeansForCategories, 'lastYear', 'debit')->renderOptions();
                
        return $this->render('FinanceOperationBundle:Category:distribution.html.twig', array(
            'charts' => $charts,
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
        $operations     = $this->getDoctrine()->getRepository('FinanceOperationBundle:Operation')->getOperations(array('category' => $category));
        $monthlyMeans   = $this->get('financeoperation.categoryhelper')->getMonthlyMeans(array('category' => $category));
        $chart          = $this->get('financeoperation.categoryhelper')->getMonthlyBalanceHistoryChart(array('category' => $category));
        
        return $this->render('FinanceOperationBundle:Category:see.html.twig', array(
            'category'      => $category,
            'operations'    => $operations,
            'monthlyMeans'  => $monthlyMeans,
            'chart'         => $chart->renderOptions(),
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
