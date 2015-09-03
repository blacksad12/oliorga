<?php

namespace Finance\OperationBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Finance\OperationBundle\Entity\Operation;
use Finance\OperationBundle\Form\OperationType;

/**
 * Operation controller.
 *
 * @Route("/operation")
 */
class OperationController extends Controller
{
    /** ************************************************************************
     * Display the Operation's homepage.
     * @Route("/")
     **************************************************************************/
    public function homeAction()        
    {
        $operations = $this->getDoctrine()
                ->getRepository('FinanceOperationBundle:Operation')
                ->findAll();
        
        return $this->render('FinanceOperationBundle:Operation:home.html.twig', array(
            'operations' => $operations
        ));
    }

    /** ************************************************************************
     * Create a new Operation according to the information given in the form.
     * @Route("/add")
     **************************************************************************/
    public function addAction()        
    {
        $operation = new Operation();
        
        $form = $this->createForm(new OperationType(), $operation);

        // ------------- Request Management ------------------------------------
        $request = $this->get('request');
        if ($request->getMethod() == 'POST') {
          $form->bind($request);// Link Request and Form

          if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($operation);
            $em->flush();

            return $this->redirect($this->generateUrl('finance_operation_operation_see', array('operation_id' => $operation->getId())));
          }
        }

        return $this->render('FinanceOperationBundle:Operation:add.html.twig', array(
            'form' => $form->createView(),
        ));
    }
    
    /** ************************************************************************
     * Display a Operation
     * @param Operation $operation
     * @ParamConverter("operation", options={"mapping": {"operation_id": "id"}})
     * @Route("/see/{operation_id}", requirements={"operation_id" = "\d+"})
     **************************************************************************/
    public function seeAction(Operation $operation)
    {
        
        return $this->render('FinanceOperationBundle:Operation:see.html.twig', array(
            'operation'      => $operation,            
          ));
    }
    
    /** ************************************************************************
     * Modify a Operation according to the information given in the form.
     * 
     * @param Operation $operation
     * @ParamConverter("operation", options={"mapping": {"operation_id": "id"}})
     * @Route("/modify/{operation_id}", requirements={"operation_id" = "\d+"})
     **************************************************************************/
    public function modifyAction(Operation $operation)
    {
        $form = $this->createForm(new OperationType($operation), $operation);

        // ------------- Request Management ------------------------------------
        $request = $this->get('request');
        if ($request->getMethod() == 'POST') {
          $form->bind($request); // Link Request and Form

          if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($operation);
            $em->flush();

            return $this->redirect($this->generateUrl('finance_operation_operation_see', array('operation_id' => $operation->getId())));
          }
        }

        return $this->render('FinanceOperationBundle:Operation:modify.html.twig', array(
            'operation' => $operation,
            'form' => $form->createView(),           
        ));
    }
    
    /** ************************************************************************
     * Delete a Operation.
     * 
     * @param Operation $operation
     * @ParamConverter("operation", options={"mapping": {"operation_id": "id"}})
     * @Route("/delete/{operation_id}", requirements={"operation_id" = "\d+"})
     **************************************************************************/
    public function deleteAction(Operation $operation)
    {
        $request = $this->get('request');
        if ($request->getMethod() == 'POST') {
            $em = $this->getDoctrine()->getManager();
            $em->remove($operation);
            $em->flush();
            return $this->redirect($this->generateUrl(/* Redirect to some page */));          
        }
        else{
            throw new \Symfony\Component\Security\Core\Exception\AccessDeniedException;
        }
    }
    
}
