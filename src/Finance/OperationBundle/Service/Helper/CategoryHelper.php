<?php

namespace Finance\OperationBundle\Service\Helper;

use Finance\OperationBundle\Entity\Category;

/**
 * CategoryHelper
 */
class CategoryHelper
{
    protected $em;
    protected $doctrine;
    protected $operationHelper;
    protected $router;
    
    /** ************************************************************************
     * Constructor
     * @param \Doctrine\ORM\EntityManager $em
     **************************************************************************/
    public function __construct(\Doctrine\ORM\EntityManager $em, $doctrine, \Finance\OperationBundle\Service\Helper\OperationHelper $operationHelper, \Symfony\Component\Routing\Router $router) {
        $this->em               = $em;
        $this->doctrine         = $doctrine;
        $this->operationHelper  = $operationHelper;
        $this->router           = $router;
    }
    
    /** ************************************************************************
     * Return the monthly balance history of the category matching the $parameters
     * and its children (if any).
     * ['current'] => [
     *      ['solde'] => float,
     *      ['date']  => /DateTime,
     * ]
     * ['children'] => 
     *      [children_id] => [
     *          ['solde'] => float,
     *          ['date']  => /DateTime,
     *      ]
     * ]
     * 
     * @param array $parameters
     * @return array
     **************************************************************************/
    public function getMonthlyBalanceHistory(array $parameters) {
        $monthlyBalanceHistory = array();
        $monthlyBalanceHistory['current'] = $this->operationHelper->getMonthlyBalanceHistory($parameters);
        foreach($parameters['category']->getChildrenCategories() as $childCategory) {
            $parameters['category'] = $childCategory;
            $monthlyBalanceHistory['children'][$childCategory->getId()] = $this->operationHelper->getMonthlyBalanceHistory($parameters);
        }
        return $monthlyBalanceHistory;
    }
    
    /** ************************************************************************
     * 
     * @param array $parameters
     * @return array
     **************************************************************************/
    public function getBalancePerCategory(array $parameters) {
        $categories = $this->doctrine->getRepository('FinanceOperationBundle:Category')->findAll();
        $soldeParCategorie = array();
        foreach($categories as $categorie) {
            $parameters['categorie'] = $categorie;
            $solde = $this->em->getRepository('FinanceOperationBundle:Operation')->getBalance($parameters);
            if($solde !== NULL) {
                $soldeParCategorie[($solde > 0 ? 'revenus' : 'depenses')][$categorie->__toString()] = $this->em->getRepository('FinanceOperationBundle:Operation')->getBalance($parameters);
            }            
        }
        return $soldeParCategorie;
    }
    
    /** ************************************************************************
     * Return the monthly means of all the Operations matching the Category 
     * described in the $parameters, and its children (if any).
     * ['current'] => [
     *      ['entity'] => Category
     *      ['mean'] => [
     *          ['one']   => float,
     *          ['three'] => float,
     *          etc.
     *      ]
     * ]
     * ['children'] => 
     *      [0] => [
     *          ['entity'] => Category
     *          ['mean'] => [
     *              ['one']   => float,
     *              ['three'] => float,
     *              etc.
     *          ]
     *      ]
     * ]
     * 
     * @param array $parameters
     * @return array
     **************************************************************************/
    public function getMonthlyMeans(array $parameters) {
        $category = $parameters['category'];
        $monthlyMeans = array();
        
        $monthlyMeans['current']['entity'] = $category;
        $monthlyMeans['current']['means'] = $this->operationHelper->getMonthlyMeans($parameters);
        foreach($category->getChildrenCategories() as $key=>$childCategory) {
            if(!$childCategory->getIsObselete()) {
                continue;
            }
            $parameters['category'] = $childCategory;
            $monthlyMeans['children'][$key]['entity'] = $childCategory;
            $monthlyMeans['children'][$key]['means']    = $this->operationHelper->getMonthlyMeans($parameters);
        }
        return $monthlyMeans;
    }
    
    /** ************************************************************************
     * 
     * @param array $parameters
     **************************************************************************/
    public function getMonthlyBalanceHistoryChart(array $parameters) {
        $chart = new \Ghunti\HighchartsPHP\Highchart();
        $this->setChartConfiguration($chart);
        
        $monthlyBalanceHistory = $this->getMonthlyBalanceHistory($parameters);
        $this->addChartSeries($chart, $parameters['category']->__toString(), $monthlyBalanceHistory['current'],true, 3);
        if(isset($monthlyBalanceHistory['children'])) {
            foreach($monthlyBalanceHistory['children'] as $categoryId=>$monthlyBalanceHistoryForChild) {
                $category = $this->doctrine->getRepository('FinanceOperationBundle:Category')->find($categoryId);
                $this->addChartSeries($chart, $category->__toString(), $monthlyBalanceHistoryForChild, false);
            }
        }
        return $chart;
    }
    
    /** ************************************************************************
     * Add a 'serie' to the $chart.
     * @param \Ghunti\HighchartsPHP\Highchart $chart
     * @param string $serieName
     * @param array $monthlyBalanceArray
     * @param integer $numberOfMonthForMean
     **************************************************************************/
    protected function addChartSeries(\Ghunti\HighchartsPHP\Highchart $chart, $serieName, array $monthlyBalanceArray, $visible=true, $numberOfMonthForMean=null) {
        $currentBalance = array();
        $currentBalanceMean = array();
        foreach($monthlyBalanceArray as $key=>$monthlyBalance) {
            $currentBalance[] = array(
                new \Ghunti\HighchartsPHP\HighchartJsExpr($this->getJavascriptDateString($monthlyBalance['date'])),
                $monthlyBalance['balance']
            );
            
            if($numberOfMonthForMean === NULL) {
                continue;
            }
            if($numberOfMonthForMean < $key+2) {
                $mean = 0;
                for($i=0; $i<$numberOfMonthForMean; $i++) {
                    $mean += $monthlyBalanceArray[$key-$i]['balance'];
                }
                $mean = $mean/$numberOfMonthForMean;
                $currentBalanceMean[] = array(
                    new \Ghunti\HighchartsPHP\HighchartJsExpr($this->getJavascriptDateString($monthlyBalance['date'])),
                    $mean
                );            
            }
        }
        $chart->series[] = array(
            'name'      => $serieName,
            'visible'   => $visible,
            'type'      => 'spline',
            'data'      => $currentBalance,
        );
        if($numberOfMonthForMean !== NULL) {
            $chart->series[] = array(
                'name' => $serieName.' (mean-'.$numberOfMonthForMean.')',
                'type' => 'spline',
                'data' => $currentBalanceMean,
            );
        }
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
            'text' => 'Monthly balance',
            'x' => - 20
        );        
        $chart->xAxis = array(
            'type' => 'datetime',
        );
        $chart->yAxis = array(
            'labels' => array(
                'format' => '{value} €',
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
        );
        $chart->tooltip->formatter = new \Ghunti\HighchartsPHP\HighchartJsExpr(
            "function() {
                return '<b>'+ this.series.name +'</b><br/>'+
                Highcharts.dateFormat('%b %Y', this.x) +': '+ this.y.toLocaleString('fr-FR', { style: 'currency', currency: 'EUR' });
            }"
        );
    }
    
    /** ************************************************************************
     * 
     * @param array $monthlyMeansArray
     * @param string $timeframe
     * @return \Ghunti\HighchartsPHP\Highchart
     **************************************************************************/
    public function getAllCategoriesMonthlyMeansCharts(array $monthlyMeansArray, $timeframe, $creditOrDebit) {
        $chart = new \Ghunti\HighchartsPHP\Highchart();
        $categoriesData     = array();
        $childrenData       = array();
        $categoriesIndex    = 0;
        $childrenIndex      = 0;
            
        foreach($monthlyMeansArray as $monthlyMeans) {
            $category = $monthlyMeans['current']['entity'];
            $value = $monthlyMeans['current']['means'][$timeframe];
            $excludeValue = $creditOrDebit === 'credit' ? ($value <= 0) : ($value >= 0);
            if($excludeValue) {
                continue;
            }
            $categoriesData[$categoriesIndex]['name']   = $category->getName();
            $categoriesData[$categoriesIndex]['y']      = abs($value);
            $categoriesData[$categoriesIndex]['color']  = $category->getHexColor();
            $categoriesData[$categoriesIndex]['url']    = $this->router->generate('finance_operation_category_see', array('category_id' => $category->getId()));
            
            if(isset($monthlyMeans['children'])){
                foreach($monthlyMeans['children'] as $childData) {
                    $value = $childData['means'][$timeframe];
                    $excludeValue = $creditOrDebit === 'credit' ? ($value <= 0) : ($value >= 0);
                    if($excludeValue) {
                        continue;
                    }
                    $childrenData[$childrenIndex]['name']   = $childData['entity']->getName();
                    $childrenData[$childrenIndex]['y']      = abs($value);
                    $childrenData[$childrenIndex]['color']  = $category->getHexColor();
                    $childrenData[$childrenIndex]['url']    = $this->router->generate('finance_operation_category_see', array('category_id' => $childData['entity']->getId()));
                    $childrenIndex++;
                }
            }
            $categoriesIndex++;
        }
        $chart->series[] = array(
            'name'      => 'Sub-category',
            'size'      => '100%',
            'innersize' => '60%',
            'data'      => $childrenData,
            'dataLabels'=> array(
                'formatter' =>  new \Ghunti\HighchartsPHP\HighchartJsExpr("function () {
                    // display label only if larger than 2%
                    return this.point.percentage > 2 ? '<b>' + this.point.name + '</b>' : null;
                }")
            ),
            'point'     => array(
                'events'    => array(
                    'click' => new \Ghunti\HighchartsPHP\HighchartJsExpr("function(e) {
                        window.open(e.point.url, '_blank');
                        e.preventDefault();
                    }")
                )
            )
        );
        $chart->series[] = array(
            'name'      => 'Category',
            'size'      => '60%',
            'data'      => $categoriesData,
            'dataLabels'=> false,
            'point'     => array(
                'events'    => array(
                    'click' => new \Ghunti\HighchartsPHP\HighchartJsExpr("function(e) {
                        window.open(e.point.url, '_blank');
                        e.preventDefault();
                    }")
                )
            ),
        );
        $this->setAllCategoriesMonthlyMeansChartConfiguration($chart, $timeframe);
        return $chart;
    }
    
    /** ************************************************************************
     * Set the Chart configuration (title, axis, legend, etc.)
     * @param \Ghunti\HighchartsPHP\Highchart $chart
     **************************************************************************/
    protected function setAllCategoriesMonthlyMeansChartConfiguration(\Ghunti\HighchartsPHP\Highchart $chart, $title) {
        $chart->chart = array(
            'type' => 'pie',
        );        
        $chart->title = array(
            'text' => null,
        );        
        $chart->yAxis = array(
            'labels' => array(
                'format' => '{value} €',
            ),
            'title' => array(
                'text' => 'Balance'
            ),
            'plotOptions' => array(
                'pie' => array(
                    'shadow' => false,
                    'center' => ['50%','50%'],
                )                
            ) 
        );
        $chart->tooltip->formatter = new \Ghunti\HighchartsPHP\HighchartJsExpr(
            "function() {
                return '<b>'+ this.key +'</b><br/>' + 
                this.y.toLocaleString('fr-FR', { style: 'currency', currency: 'EUR' }) + ' (' + this.percentage.toFixed(1)+ ' %)';
            }"
        );
    }
}
