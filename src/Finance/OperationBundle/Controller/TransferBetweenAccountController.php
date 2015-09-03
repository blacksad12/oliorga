<?php

namespace Finance\OperationBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Finance\OperationBundle\Entity\TransferBetweenAccount;
use Finance\OperationBundle\Form\TransferBetweenAccountType;

/**
 * TransferBetweenAccount controller.
 *
 * @Route("/transferbetweenaccount")
 */
class TransferBetweenAccountController extends Controller
{
    /** ************************************************************************
     * Display the TransferBetweenAccount's homepage.
     * @Route("/")
     **************************************************************************/
    public function homeAction()        
    {
        $transferbetweenaccounts = $this->getDoctrine()
                ->getRepository('FinanceOperationBundle:TransferBetweenAccount')
                ->findAll();
        
        return $this->render('FinanceOperationBundle:TransferBetweenAccount:home.html.twig', array(
            'transferbetweenaccounts' => $transferbetweenaccounts
        ));
    }

    /** ************************************************************************
     * Create a new TransferBetweenAccount according to the information given in the form.
     * @Route("/add")
     **************************************************************************/
    public function addAction()        
    {
        $transferbetweenaccount = new TransferBetweenAccount();
        
        $form = $this->createForm(new TransferBetweenAccountType(), $transferbetweenaccount);

        // ------------- Request Management ------------------------------------
        $request = $this->get('request');
        if ($request->getMethod() == 'POST') {
          $form->bind($request);// Link Request and Form

          if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($transferbetweenaccount);
            $em->flush();

            return $this->redirect($this->generateUrl('finance_operation_transferbetweenaccount_see', array('transferbetweenaccount_id' => $transferbetweenaccount->getId())));
          }
        }

        return $this->render('FinanceOperationBundle:TransferBetweenAccount:add.html.twig', array(
            'form' => $form->createView(),
        ));
    }
    
    /** ************************************************************************
     * Display a TransferBetweenAccount
     * @param TransferBetweenAccount $transferbetweenaccount
     * @ParamConverter("transferbetweenaccount", options={"mapping": {"transferbetweenaccount_id": "id"}})
     * @Route("/see/{transferbetweenaccount_id}", requirements={"transferbetweenaccount_id" = "\d+"})
     **************************************************************************/
    public function seeAction(TransferBetweenAccount $transferbetweenaccount)
    {
        
        return $this->render('FinanceOperationBundle:TransferBetweenAccount:see.html.twig', array(
            'transferbetweenaccount'      => $transferbetweenaccount,            
          ));
    }
    
    /** ************************************************************************
     * Modify a TransferBetweenAccount according to the information given in the form.
     * 
     * @param TransferBetweenAccount $transferbetweenaccount
     * @ParamConverter("transferbetweenaccount", options={"mapping": {"transferbetweenaccount_id": "id"}})
     * @Route("/modify/{transferbetweenaccount_id}", requirements={"transferbetweenaccount_id" = "\d+"})
     **************************************************************************/
    public function modifyAction(TransferBetweenAccount $transferbetweenaccount)
    {
        $form = $this->createForm(new TransferBetweenAccountType($transferbetweenaccount), $transferbetweenaccount);

        // ------------- Request Management ------------------------------------
        $request = $this->get('request');
        if ($request->getMethod() == 'POST') {
          $form->bind($request); // Link Request and Form

          if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($transferbetweenaccount);
            $em->flush();

            return $this->redirect($this->generateUrl('finance_operation_transferbetweenaccount_see', array('transferbetweenaccount_id' => $transferbetweenaccount->getId())));
          }
        }

        return $this->render('FinanceOperationBundle:TransferBetweenAccount:modify.html.twig', array(
            'transferbetweenaccount' => $transferbetweenaccount,
            'form' => $form->createView(),           
        ));
    }
    
    /** ************************************************************************
     * Delete a TransferBetweenAccount.
     * 
     * @param TransferBetweenAccount $transferbetweenaccount
     * @ParamConverter("transferbetweenaccount", options={"mapping": {"transferbetweenaccount_id": "id"}})
     * @Route("/delete/{transferbetweenaccount_id}", requirements={"transferbetweenaccount_id" = "\d+"})
     **************************************************************************/
    public function deleteAction(TransferBetweenAccount $transferbetweenaccount)
    {
        $request = $this->get('request');
        if ($request->getMethod() == 'POST') {
            $em = $this->getDoctrine()->getManager();
            $em->remove($transferbetweenaccount);
            $em->flush();
            return $this->redirect($this->generateUrl(/* Redirect to some page */));          
        }
        else{
            throw new \Symfony\Component\Security\Core\Exception\AccessDeniedException;
        }
    }
    
}
