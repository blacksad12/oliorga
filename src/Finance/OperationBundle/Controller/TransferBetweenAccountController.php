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
        $transferBetweenAccounts = $this->getDoctrine()
                ->getRepository('FinanceOperationBundle:TransferBetweenAccount')
                ->findAll();
        
        return $this->render('FinanceOperationBundle:TransferBetweenAccount:home.html.twig', array(
            'transferBetweenAccounts' => $transferBetweenAccounts
        ));
    }

    /** ************************************************************************
     * Create a new TransferBetweenAccount according to the information given in the form.
     * @param \Finance\AccountBundle\Entity\Account $account
     * @ParamConverter("account", options={"mapping": {"account_id": "id"}})
     * @Route("/add/{account_id}", requirements={"account_id" = "\d+"})
     **************************************************************************/
    public function addAction(\Finance\AccountBundle\Entity\Account $account)        
    {
        $transferBetweenAccount = new TransferBetweenAccount();
        $transferBetweenAccount->setSourceAccount($account);
        $transferBetweenAccount->setDestinationAccount($account);
        
        $form = $this->createForm(new TransferBetweenAccountType(), $transferBetweenAccount);

        // ------------- Request Management ------------------------------------
        $request = $this->get('request');
        if ($request->getMethod() == 'POST') {
          $form->bind($request);// Link Request and Form

          if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($transferBetweenAccount);
            $em->flush();

            return $this->redirect($this->generateUrl('finance_operation_transferbetweenaccount_see', array('transferBetweenAccount_id' => $transferBetweenAccount->getId())));
          }
        }

        return $this->render('FinanceOperationBundle:TransferBetweenAccount:add.html.twig', array(
            'form'      => $form->createView(),
            'account'   => $account,
        ));
    }
    
    /** ************************************************************************
     * Display a TransferBetweenAccount
     * @param TransferBetweenAccount $transferBetweenAccount
     * @ParamConverter("transferBetweenAccount", options={"mapping": {"transferBetweenAccount_id": "id"}})
     * @Route("/see/{transferBetweenAccount_id}", requirements={"transferBetweenAccount_id" = "\d+"})
     **************************************************************************/
    public function seeAction(TransferBetweenAccount $transferBetweenAccount)
    {
        
        return $this->render('FinanceOperationBundle:TransferBetweenAccount:see.html.twig', array(
            'transferBetweenAccount'      => $transferBetweenAccount,            
          ));
    }
    
    /** ************************************************************************
     * Modify a TransferBetweenAccount according to the information given in the form.
     * 
     * @param TransferBetweenAccount $transferBetweenAccount
     * @ParamConverter("transferBetweenAccount", options={"mapping": {"transferBetweenAccount_id": "id"}})
     * @Route("/modify/{transferBetweenAccount_id}", requirements={"transferBetweenAccount_id" = "\d+"})
     **************************************************************************/
    public function modifyAction(TransferBetweenAccount $transferBetweenAccount)
    {
        $form = $this->createForm(new TransferBetweenAccountType($transferBetweenAccount), $transferBetweenAccount);

        // ------------- Request Management ------------------------------------
        $request = $this->get('request');
        if ($request->getMethod() == 'POST') {
          $form->bind($request); // Link Request and Form

          if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($transferBetweenAccount);
            $em->flush();

            return $this->redirect($this->generateUrl('finance_operation_transferbetweenaccount_see', array('transferBetweenAccount_id' => $transferBetweenAccount->getId())));
          }
        }

        return $this->render('FinanceOperationBundle:TransferBetweenAccount:modify.html.twig', array(
            'transferBetweenAccount' => $transferBetweenAccount,
            'form' => $form->createView(),           
        ));
    }
    
    /** ************************************************************************
     * Duplicate a TransferBetweenAccount.
     * 
     * @param TransferBetweenAccount $oldTransferBetweenAccount
     * @ParamConverter("oldTransferBetweenAccount", options={"mapping": {"oldTransferBetweenAccount_id": "id"}})
     * @Route("/duplicate/{oldTransferBetweenAccount_id}", requirements={"oldTransferBetweenAccount_id" = "\d+"})
     **************************************************************************/
    public function duplicateAction(TransferBetweenAccount $oldTransferBetweenAccount)
    {
        $newTransferBetweenAccount = new TransferBetweenAccount();
        $this->get('financeoperation.transferbetweenaccounthelper')->duplicate($oldTransferBetweenAccount, $newTransferBetweenAccount);
        $form = $this->createForm(new TransferBetweenAccountType($newTransferBetweenAccount), $newTransferBetweenAccount);

        // ------------- Request Management ------------------------------------
        $request = $this->get('request');
        if ($request->getMethod() == 'POST') {
          $form->bind($request); // Link Request and Form

          if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($newTransferBetweenAccount);
            $em->flush();

            return $this->redirect($this->generateUrl('finance_operation_transferbetweenaccount_see', array('transferBetweenAccount_id' => $newTransferBetweenAccount->getId())));
          }
        }

        return $this->render('FinanceOperationBundle:TransferBetweenAccount:add.html.twig', array(
            'oldTransferBetweenAccount' => $oldTransferBetweenAccount,
            'form' => $form->createView(),           
        ));
    }
    
    /** ************************************************************************
     * Delete a TransferBetweenAccount.
     * 
     * @param TransferBetweenAccount $transferBetweenAccount
     * @ParamConverter("transferBetweenAccount", options={"mapping": {"transferBetweenAccount_id": "id"}})
     * @Route("/delete/{transferBetweenAccount_id}", requirements={"transferBetweenAccount_id" = "\d+"})
     **************************************************************************/
    public function deleteAction(TransferBetweenAccount $transferBetweenAccount)
    {
        $request = $this->get('request');
        if ($request->getMethod() == 'POST') {
            $em = $this->getDoctrine()->getManager();
            $em->remove($transferBetweenAccount);
            $em->flush();
            return $this->redirect($this->generateUrl(/* Redirect to some page */));          
        }
        else{
            throw new \Symfony\Component\Security\Core\Exception\AccessDeniedException;
        }
    }
    
}
