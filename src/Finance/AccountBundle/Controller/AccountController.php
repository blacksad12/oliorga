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
        $accountArray = array();
        $total = 0;
        foreach($accounts as $key=>$account) {
            $accountArray[$key]['account'] = $account;
            $balance = $this->getDoctrine()->getRepository('FinanceOperationBundle:AbstractOperation')->getBalance(array('account' => $account));
            $accountArray[$key]['balance'] = $balance;
            $total += $balance;
        }
        
        return $this->render('FinanceAccountBundle:Account:home.html.twig', array(
            'accountArray'  => $accountArray,
            'total'         => $total,
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
        $now = new \DateTime();
        $operations         = $em->getRepository('FinanceOperationBundle:Operation')->getOperations(array('account' => $account, 'before' => $now));
        $incomeTransfers    = $em->getRepository('FinanceOperationBundle:TransferBetweenAccount')->getOperations(array('destinationAccount' => $account, 'before' => $now));
        $outcomeTransfers   = $em->getRepository('FinanceOperationBundle:TransferBetweenAccount')->getOperations(array('sourceAccount' => $account, 'before' => $now));
        
        $balances = array();
        $balances['today'] = $em->getRepository('FinanceOperationBundle:AbstractOperation')->getBalance(array(
            'account'   => $account,
            'before'    => $now,
            ));
        $balances['marked'] = $em->getRepository('FinanceOperationBundle:AbstractOperation')->getBalance(array(
            'account'   => $account,
            'isMarked'  => true,
            ));
        $balances['thisMonth'] = $em->getRepository('FinanceOperationBundle:AbstractOperation')->getBalance(array(
            'account'   => $account,
            'before'    => $now->modify("last day of this month"),
            ));
        $balances['nextMonth'] = $em->getRepository('FinanceOperationBundle:AbstractOperation')->getBalance(array(
            'account'   => $account,
            'before'    => $now->modify("last day of next month"),
            ));
        
        return $this->render('FinanceAccountBundle:Account:see.html.twig', array(
            'account'           => $account,
            'operations'        => $operations,
            'incomeTransfers'   => $incomeTransfers,
            'outcomeTransfers'  => $outcomeTransfers,
            'balances'          => $balances,
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
    
    /** ************************************************************************
     * 
     * @Route("/displayoperationsofmonth")
     **************************************************************************/
    public function displayOperationsOfMonthAction()        
    {
        $em                 = $this->getDoctrine()->getManager();
        $request            = $this->get('request');
        $account            = $this->getDoctrine()->getRepository('FinanceAccountBundle:Account')->find($request->request->get('accountId'));
        
        $beginningOfMonth   = \DateTime::createFromFormat('Y-m-d', $request->get('year').'-'.$request->get('month').'-01');
        $endOfMonth         = clone $beginningOfMonth;
        $endOfMonth->modify('last day of this month');
        
        $abstractOperations = array();
        $abstractOperations['operations']       = $em->getRepository('FinanceOperationBundle:Operation')->getOperations(array('account' => $account, 'before' => $endOfMonth, 'after' => $beginningOfMonth));
        $abstractOperations['incomeTransfers']  = $em->getRepository('FinanceOperationBundle:TransferBetweenAccount')->getOperations(array('destinationAccount' => $account, 'before' => $endOfMonth, 'after' => $beginningOfMonth));
        $abstractOperations['outcomeTransfers'] = $em->getRepository('FinanceOperationBundle:TransferBetweenAccount')->getOperations(array('sourceAccount' => $account, 'before' => $endOfMonth, 'after' => $beginningOfMonth));
        $abstractOperations['recurrentOperations'] = $em->getRepository('FinanceOperationBundle:Operation')->getOperations(array('account' => $account, 'isMonthlyRecurrent' => true));
        
        return $this->render('FinanceAccountBundle:Account:seeOperationsTable.html.twig', array(
            'beginningOfMonth'      => $beginningOfMonth,
            'endOfMonth'            => $endOfMonth,
            'abstractOperations'    => $abstractOperations,
        ));
    }
    
    
    
}
