<?php

namespace Finance\OperationBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Finance\OperationBundle\Entity\Operation;
use Finance\OperationBundle\Form\RecurrentOperationType;

/**
 * RecurrentOperation controller.
 *
 * @Route("/recurrentoperation")
 */
class RecurrentOperationController extends Controller
{
    /** ************************************************************************
     * Create a new Operation according to the information given in the form.
     * @param \Finance\AccountBundle\Entity\Account $account
     * @ParamConverter("account", options={"mapping": {"account_id": "id"}})
     * @Route("/add/{account_id}", requirements={"account_id" = "\d+"})
     **************************************************************************/
    public function addAction(\Finance\AccountBundle\Entity\Account $account)        
    {
        $recurrentOperation = new Operation($account);
        $recurrentOperation->setIsMonthlyRecurrent(true);
        $recurrentOperation->setDate(new \DateTime());
        $recurrentOperation->setIsMarked(false);
        
        $form = $this->createForm(new RecurrentOperationType(), $recurrentOperation);

        // ------------- Request Management ------------------------------------
        $request = $this->get('request');
        if ($request->getMethod() == 'POST') {
          $form->bind($request);// Link Request and Form

          if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($recurrentOperation);
            $em->flush();

            return $this->redirect($this->generateUrl('finance_operation_operation_see', array('operation_id' => $recurrentOperation->getId())));
          }
        }

        return $this->render('FinanceOperationBundle:RecurrentOperation:add.html.twig', array(
            'form'      => $form->createView(),
            'account'   => $account,
        ));
    }
    
    /** ************************************************************************
     * Display a RecurrentOperation
     * @param Operation $recurrentOperation
     * @ParamConverter("recurrentOperation", options={"mapping": {"recurrentOperation_id": "id"}})
     * @Route("/see/{recurrentOperation_id}", requirements={"recurrentOperation_id" = "\d+"})
     **************************************************************************/
    public function seeAction(Operation $recurrentOperation)
    {
        
        return $this->render('FinanceOperationBundle:RecurrentOperation:see.html.twig', array(
            'recurrentOperation'    => $recurrentOperation,            
          ));
    }
    
    /** ************************************************************************
     * Modify a RecurrentOperation according to the information given in the form.
     * 
     * @param Operation $recurrentOperation
     * @ParamConverter("recurrentOperation", options={"mapping": {"recurrentOperation_id": "id"}})
     * @Route("/modify/{recurrentOperation_id}", requirements={"recurrentOperation_id" = "\d+"})
     **************************************************************************/
    public function modifyAction(Operation $recurrentOperation)
    {
        $form = $this->createForm(new RecurrentOperationType($recurrentOperation), $recurrentOperation);

        // ------------- Request Management ------------------------------------
        $request = $this->get('request');
        if ($request->getMethod() == 'POST') {
          $form->bind($request); // Link Request and Form

          if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($recurrentOperation);
            $em->flush();

            return $this->redirect($this->generateUrl('finance_operation_recurrentoperation_see', array('recurrentOperation_id' => $recurrentOperation->getId())));
          }
        }

        return $this->render('FinanceOperationBundle:RecurrentOperation:modify.html.twig', array(
            'recurrentOperation'    => $recurrentOperation,
            'form'                  => $form->createView(),           
        ));
    }
        
    /** ************************************************************************
     * Delete a RecurrentOperation.
     * 
     * @param Operation $recurrentOperation
     * @ParamConverter("recurrentOperation", options={"mapping": {"recurrentOperation_id": "id"}})
     * @Route("/delete/{recurrentOperation_id}", requirements={"recurrentOperation_id" = "\d+"})
     **************************************************************************/
    public function deleteAction(Operation $recurrentOperation)
    {
        $request = $this->get('request');
        if ($request->getMethod() == 'POST') {
            $em = $this->getDoctrine()->getManager();
            $em->remove($recurrentOperation);
            $em->flush();
            return $this->redirect($this->generateUrl('finance_operation_recurrentoperation_see', array('recurrentOperation_id' => $recurrentOperation->getId())));          
        }
        else{
            throw new \Symfony\Component\Security\Core\Exception\AccessDeniedException;
        }
    }
    
}
