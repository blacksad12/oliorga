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
        return $this->render('FinanceAccountBundle:Dashboard:home.html.twig');
    }
    
    /** ************************************************************************
     * Display the Savings dashboard.
     * @Route("/seesavings")
     **************************************************************************/
    public function seeSavingsAction()
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
