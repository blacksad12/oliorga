<?php

namespace Finance\AccountBundle\Service\Helper;

/**
 * SavingsHelper
 */
class SavingsHelper
{
    protected $em;
    
    /** ************************************************************************
     * Constructor
     * @param \Doctrine\ORM\EntityManager $em
     **************************************************************************/
    public function __construct(\Doctrine\ORM\EntityManager $em) {
        $this->em = $em;
    }
    
    /** ************************************************************************
     * Get an array with the balance for each month of the Operations matching 
     * criteria defined in $parameters
     * 0 => [
     *      ['balance'] => float,
     *      ['savings'] => float,
     *      ['date']    => /DateTime,
     * ],
     * 1 => etc.
     * @param array $parameters
     * @return array
     **************************************************************************/
    public function getMonthlyBalanceHistory(array $parameters) {
        $startDate  = new \DateTime("2008-09-30");
        $endDate    = new \DateTime();
        $endOfMonth = $startDate;
        $i = 0;
        
        $monthlyBalanceHistory = array();
        
        while($endOfMonth < $endDate){
            $startOfMonth = clone $endOfMonth;
            $startOfMonth->modify('first day of this month'); 
            
            //$parameters['startDate'] = $startDate;
            $parameters['endDate']   = $endOfMonth;
            $balance                 = $this->em->getRepository('FinanceOperationBundle:Operation')->getBalance($parameters);
            $monthlyBalanceHistory[$i]['balance']   = $balance === NULL ? 0 : floatval($balance);
            
            if($i>0) {
                $savings = $balance - $monthlyBalanceHistory[$i-1]['balance'];
                $monthlyBalanceHistory[$i]['savings'] = $savings;
            } else {
                $monthlyBalanceHistory[$i]['savings'] = 0;
            }
            
            $monthlyBalanceHistory[$i]['balance']   = $balance === NULL ? 0 : floatval($balance);
            $monthlyBalanceHistory[$i]['date']      = clone $endOfMonth;
            
            $endOfMonth->modify('last day of next month');
            $i++;
        }
        return $monthlyBalanceHistory;
    }
        
    /** ************************************************************************
     * Get an array containing the monthly means of the amount of each Operation 
     * matching $parameters, for the following timeframes:
     * - ['one']      => from first day of last month to last day of last month
     * - ['three']    => from first day of 3 month ago to last day of last month
     * - ['six']      => from first day of 6 month ago to last day of last month
     * - ['thisYear'] => from 1st January this year to 31st December this year
     * - ['lastYear'] => from 1st January last year to 31st December last year
     * @param array $parameters
     * @return array
     **************************************************************************/
    public function getMonthlySavingsMeans(array $monthlyBalanceHistory) {
        return array(
            'one'       => $this->getLastMonthMonthlyMean($monthlyBalanceHistory),
            'three'     => $this->getLastXMonthsMonthlyMean($monthlyBalanceHistory,3),
            'six'       => $this->getLastXMonthsMonthlyMean($monthlyBalanceHistory,6),
            'thisYear'  => $this->getThisYearMonthlyMean($monthlyBalanceHistory),
            'lastYear'  => $this->getLastYearMonthlyMean($monthlyBalanceHistory),
        );
    }
    
    /** ************************************************************************
     * GetLastMonthMonthlyMean
     * @param array $parameters
     * @return float
     **************************************************************************/
    public function getLastMonthMonthlyMean(array $monthlyBalanceHistory) {
        $lastMonthIndex = count($monthlyBalanceHistory);
        return $monthlyBalanceHistory[$lastMonthIndex-1]['savings'];
    }
    
    /** ************************************************************************
     * GetLastXMonthsMonthlyMean
     * @param array $parameters
     * @return float
     **************************************************************************/
    public function getLastXMonthsMonthlyMean(array $monthlyBalanceHistory, $numberOfMonths) {
        $lastMonthIndex = count($monthlyBalanceHistory);
        $savingsSum = 0;
        for($i=$lastMonthIndex-$numberOfMonths;$i<$lastMonthIndex;$i++) {
            $savingsSum += $monthlyBalanceHistory[$i]['savings'];
        }
        return $savingsSum/$numberOfMonths;
    }
    
    /** ************************************************************************
     * GetThisYearMonthlyMean
     * @param array $parameters
     * @return float
     **************************************************************************/
    public function getThisYearMonthlyMean(array $monthlyBalanceHistory) {
        $now = new \DateTime;
        $numberOfMonths = intval($now->format('m'));
        return $this->getLastXMonthsMonthlyMean($monthlyBalanceHistory, $numberOfMonths);
    }
    
    /** ************************************************************************
     * GetLastYearMonthlyMean
     * @param array $parameters
     * @return float
     **************************************************************************/
    public function getLastYearMonthlyMean(array $monthlyBalanceHistory) {
        $lastMonthIndex = count($monthlyBalanceHistory);
        $now = new \DateTime;
        $numberOfMonthsThisYear = intval($now->format('m'));
        $savingsSum = 0;
        for($i=$lastMonthIndex-$numberOfMonthsThisYear-12;$i<$lastMonthIndex-$numberOfMonthsThisYear;$i++) {
            $savingsSum += $monthlyBalanceHistory[$i]['savings'];
        }
        return $savingsSum/12;
    }
    
    /** ************************************************************************
     * 
     * @param array $parameters
     **************************************************************************/
    public function getMonthlyBalanceHistoryChart(array $monthlyBalanceHistory) {
        $chart = new \Ghunti\HighchartsPHP\Highchart();
        $this->setChartConfiguration($chart);
                
        $this->addChartSeries($chart, $monthlyBalanceHistory);
        
        return $chart;
    }
    
    /** ************************************************************************
     * Add a 'serie' to the $chart.
     * @param \Ghunti\HighchartsPHP\Highchart $chart
     * @param string $serieName
     * @param array $monthlyBalanceArray
     * @param integer $numberOfMonthForMean
     **************************************************************************/
    protected function addChartSeries(\Ghunti\HighchartsPHP\Highchart $chart, array $monthlyBalanceArray) {
        $balanceData = array();
        $monthlySavingsData = array();
        foreach($monthlyBalanceArray as $key=>$monthlyBalance) {
            $balanceData[] = array(
                new \Ghunti\HighchartsPHP\HighchartJsExpr($this->getJavascriptDateString($monthlyBalance['date'])),
                $monthlyBalance['balance']
            );
            $monthlySavingsData[] = array(
                new \Ghunti\HighchartsPHP\HighchartJsExpr($this->getJavascriptDateString($monthlyBalance['date'])),
                $monthlyBalance['savings']
            );
        }
        
        $chart->series[] = array(
            'name'      => 'Balance',
            'type'      => 'spline',
            'data'      => $balanceData,
        );        
        $chart->series[] = array(
            'name' => 'Savings',
            'type' => 'column',
            'data' => $monthlySavingsData,
            'yAxis' => 1,
        );
        
    }


    /** ************************************************************************
     * Convert a \DateTime into a string for Javascript
     * Expl: Date.UTC("2015","0","1") for January 1st, 2015
     * @param \DateTime $phpDate
     * @return string
     **************************************************************************/
    protected function getJavascriptDateString(\DateTime $phpDate) {
        $year   = $phpDate->format('Y');
        $month  = intval($phpDate->format('m'))-1;
        $day    = $phpDate->format('d');
        return "Date.UTC(".$year.",".$month.",".$day.")";        
    }
    
    /** ************************************************************************
     * Set the Chart configuration (title, axis, legend, etc.)
     * @param \Ghunti\HighchartsPHP\Highchart $chart
     **************************************************************************/
    protected function setChartConfiguration(\Ghunti\HighchartsPHP\Highchart $chart) {
        $chart->title = array(
            'text' => 'Global balance evolution',
            'x' => - 20
        );        
        $chart->xAxis = array(
            'type' => 'datetime',
        );
        $chart->yAxis = array(
            array(
                'labels' => array(
                    'format' => '{value:,.0f} €',
                ),
                'title' => array(
                    'text' => 'Balance'
                ),  
                'plotLines' => array(
                    array(
                        'color' => '#C0C0C0',
                        'width' => 5,
                        'value' => 0
                    )                
                ) 
            ),
            array(
                'labels' => array(
                    'format' => '{value:,.0f} €',
                ),
                'title' => array(
                    'text' => 'Savings'
                ),  
                'opposite' => true,
            )
        );
        $chart->legend = array(
            'layout' => 'vertical',
            'align' => 'right',
            'verticalAlign' => 'middle',
            'borderWidth' => 0
        );
        $chart->tooltip->formatter = new \Ghunti\HighchartsPHP\HighchartJsExpr(
            "function() {
                return '<b>'+ this.series.name +'</b><br/>'+
                Highcharts.dateFormat('%b %Y', this.x) +': '+ this.y.toLocaleString('fr-FR', { style: 'currency', currency: 'EUR' });
            }"
        );
    
    }
}
