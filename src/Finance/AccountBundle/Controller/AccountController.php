<?php

namespace Finance\AccountBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Finance\AccountBundle\Entity\Account;
use Finance\AccountBundle\Form\AccountType;

/**
 * Account controller.
 *
 * @Route("/account")
 */
class AccountController extends Controller
{
    /** ************************************************************************
     * Display the Account's homepage.
     * @Route("/")
     **************************************************************************/
    public function homeAction()        
    {
        $accounts = $this->getDoctrine()
                ->getRepository('FinanceAccountBundle:Account')
                ->findAll();
        
        return $this->render('FinanceAccountBundle:Account:home.html.twig', array(
            'accounts' => $accounts
        ));
    }

    /** ************************************************************************
     * Create a new Account according to the information given in the form.
     * @Route("/add")
     **************************************************************************/
    public function addAction()        
    {
        $account = new Account();
        
        $form = $this->createForm(new AccountType(), $account);

        // ------------- Request Management ------------------------------------
        $request = $this->get('request');
        if ($request->getMethod() == 'POST') {
          $form->bind($request);// Link Request and Form

          if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($account);
            $em->flush();

            return $this->redirect($this->generateUrl('finance_account_account_see', array('account_id' => $account->getId())));
          }
        }

        return $this->render('FinanceAccountBundle:Account:add.html.twig', array(
            'form' => $form->createView(),
        ));
    }
    
    /** ************************************************************************
     * Display a Account
     * @param Account $account
     * @ParamConverter("account", options={"mapping": {"account_id": "id"}})
     * @Route("/see/{account_id}", requirements={"account_id" = "\d+"})
     **************************************************************************/
    public function seeAction(Account $account)
    {
        $em = $this->getDoctrine()->getManager();
        $operations         = $em->getRepository('FinanceOperationBundle:Operation')->findByAccount($account);
        $incomeTransfers    = $em->getRepository('FinanceOperationBundle:TransferBetweenAccount')->findByDestinationAccount($account);
        $outcomeTransfers   = $em->getRepository('FinanceOperationBundle:TransferBetweenAccount')->findBySourceAccount($account);
        
        $balance = $em->getRepository('FinanceOperationBundle:AbstractOperation')->getBalance(array(
            'account'   => $account,
            ));
        $markedBalance = $em->getRepository('FinanceOperationBundle:AbstractOperation')->getBalance(array(
            'account'   => $account,
            'isMarked'  => true,
            ));
        
        return $this->render('FinanceAccountBundle:Account:see.html.twig', array(
            'account'           => $account,
            'operations'        => $operations,
            'incomeTransfers'   => $incomeTransfers,
            'outcomeTransfers'  => $outcomeTransfers,
            'balance'           => $balance,
            'markedBalance'     => $markedBalance,
          ));
    }
    
    /** ************************************************************************
     * Modify a Account according to the information given in the form.
     * 
     * @param Account $account
     * @ParamConverter("account", options={"mapping": {"account_id": "id"}})
     * @Route("/modify/{account_id}", requirements={"account_id" = "\d+"})
     **************************************************************************/
    public function modifyAction(Account $account)
    {
        $form = $this->createForm(new AccountType($account), $account);

        // ------------- Request Management ------------------------------------
        $request = $this->get('request');
        if ($request->getMethod() == 'POST') {
          $form->bind($request); // Link Request and Form

          if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($account);
            $em->flush();

            return $this->redirect($this->generateUrl('finance_account_account_see', array('account_id' => $account->getId())));
          }
        }

        return $this->render('FinanceAccountBundle:Account:modify.html.twig', array(
            'account' => $account,
            'form' => $form->createView(),           
        ));
    }
    
    /** ************************************************************************
     * Delete a Account.
     * 
     * @param Account $account
     * @ParamConverter("account", options={"mapping": {"account_id": "id"}})
     * @Route("/delete/{account_id}", requirements={"account_id" = "\d+"})
     **************************************************************************/
    public function deleteAction(Account $account)
    {
        $request = $this->get('request');
        if ($request->getMethod() == 'POST') {
            $em = $this->getDoctrine()->getManager();
            $em->remove($account);
            $em->flush();
            return $this->redirect($this->generateUrl(/* Redirect to some page */));          
        }
        else{
            throw new \Symfony\Component\Security\Core\Exception\AccessDeniedException;
        }
    }
    
}
