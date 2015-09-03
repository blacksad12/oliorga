<?php

namespace Finance\OperationBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Finance\OperationBundle\Entity\Imputation;
use Finance\OperationBundle\Form\ImputationType;

/**
 * Imputation controller.
 *
 * @Route("/imputation")
 */
class ImputationController extends Controller
{
    /** ************************************************************************
     * Display the Imputation's homepage.
     * @Route("/")
     **************************************************************************/
    public function homeAction()        
    {
        $imputations = $this->getDoctrine()
                ->getRepository('FinanceOperationBundle:Imputation')
                ->findAll();
        
        return $this->render('FinanceOperationBundle:Imputation:home.html.twig', array(
            'imputations' => $imputations
        ));
    }

    /** ************************************************************************
     * Create a new Imputation according to the information given in the form.
     * @Route("/add")
     **************************************************************************/
    public function addAction()        
    {
        $imputation = new Imputation();
        
        $form = $this->createForm(new ImputationType(), $imputation);

        // ------------- Request Management ------------------------------------
        $request = $this->get('request');
        if ($request->getMethod() == 'POST') {
          $form->bind($request);// Link Request and Form

          if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($imputation);
            $em->flush();

            return $this->redirect($this->generateUrl('finance_operation_imputation_see', array('imputation_id' => $imputation->getId())));
          }
        }

        return $this->render('FinanceOperationBundle:Imputation:add.html.twig', array(
            'form' => $form->createView(),
        ));
    }
    
    /** ************************************************************************
     * Display a Imputation
     * @param Imputation $imputation
     * @ParamConverter("imputation", options={"mapping": {"imputation_id": "id"}})
     * @Route("/see/{imputation_id}", requirements={"imputation_id" = "\d+"})
     **************************************************************************/
    public function seeAction(Imputation $imputation)
    {
        
        return $this->render('FinanceOperationBundle:Imputation:see.html.twig', array(
            'imputation'      => $imputation,            
          ));
    }
    
    /** ************************************************************************
     * Modify a Imputation according to the information given in the form.
     * 
     * @param Imputation $imputation
     * @ParamConverter("imputation", options={"mapping": {"imputation_id": "id"}})
     * @Route("/modify/{imputation_id}", requirements={"imputation_id" = "\d+"})
     **************************************************************************/
    public function modifyAction(Imputation $imputation)
    {
        $form = $this->createForm(new ImputationType($imputation), $imputation);

        // ------------- Request Management ------------------------------------
        $request = $this->get('request');
        if ($request->getMethod() == 'POST') {
          $form->bind($request); // Link Request and Form

          if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($imputation);
            $em->flush();

            return $this->redirect($this->generateUrl('finance_operation_imputation_see', array('imputation_id' => $imputation->getId())));
          }
        }

        return $this->render('FinanceOperationBundle:Imputation:modify.html.twig', array(
            'imputation' => $imputation,
            'form' => $form->createView(),           
        ));
    }
    
    /** ************************************************************************
     * Delete a Imputation.
     * 
     * @param Imputation $imputation
     * @ParamConverter("imputation", options={"mapping": {"imputation_id": "id"}})
     * @Route("/delete/{imputation_id}", requirements={"imputation_id" = "\d+"})
     **************************************************************************/
    public function deleteAction(Imputation $imputation)
    {
        $request = $this->get('request');
        if ($request->getMethod() == 'POST') {
            $em = $this->getDoctrine()->getManager();
            $em->remove($imputation);
            $em->flush();
            return $this->redirect($this->generateUrl(/* Redirect to some page */));          
        }
        else{
            throw new \Symfony\Component\Security\Core\Exception\AccessDeniedException;
        }
    }
    
}
