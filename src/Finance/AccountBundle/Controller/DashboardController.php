<?php

namespace Finance\AccountBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;


/**
 * Dashboard controller.
 *
 * @Route("/dashboard")
 */
class DashboardController extends Controller
{
    /** ************************************************************************
     * Display dashboard's home.
     * @Route("/")
     **************************************************************************/
    public function homeAction()
    {        
        $users = $this->getDoctrine()
                ->getRepository('OliorgaAppBundle:User')
                ->findAll();
        return $this->render('FinanceAccountBundle:Dashboard:home.html.twig', array(
            'users' => $users,
        ));
    }
    
    /** ************************************************************************
     * Display the Savings dashboard (only for $user, if any)
     * @param \Oliorga\AppBundle\Entity\User $user
     * @ParamConverter("user", options={"mapping": {"user_id": "id"}})
     * @Route("/seesavings/{user_id}", requirements={"user_id" = "\d+"}, name="finance_account_dashboard_seesavingsforuser")
     * @Route("/seesavings")
     **************************************************************************/
    public function seeSavingsAction(\Oliorga\AppBundle\Entity\User $user = NULL)
    {
        $monthlyBalanceHistory  = $this->get('financeaccount.savingshelper')->getMonthlyBalanceHistory(array());
        $monthlySavingsMeans    = $this->get('financeaccount.savingshelper')->getMonthlySavingsMeans($monthlyBalanceHistory);
        $chart                  = $this->get('financeaccount.savingshelper')->getMonthlyBalanceHistoryChart($monthlyBalanceHistory);
        
        return $this->render('FinanceAccountBundle:Dashboard:seeSavings.html.twig', array(
            'monthlyBalanceHistory' => $monthlyBalanceHistory,
            'monthlySavingsMeans'   => $monthlySavingsMeans,
            'chart'                 => $chart->renderOptions(),
        ));
    }
    
}
