<?php

namespace Finance\OperationBundle\Tests\Controller;

use Oliorga\CoreBundle\Tests\Controller\AbstractControllerTest;

class CategoryControllerTest extends AbstractControllerTest
{
    /** ************************************************************************
     * Return an array containing one array per Controller's action, with the 
     * route data.
     * @return array
     **************************************************************************/
    public function getRouteData() {
        $categoryId = 1;
        $rootUrl = '/operation/category';        
        return array(
            array('data' => array('method' => 'GET', 'url' => $rootUrl.'/')),
            array('data' => array('method' => 'GET', 'url' => $rootUrl.'/add')),
            array('data' => array('method' => 'GET', 'url' => $rootUrl.'/see/'.$categoryId)),
            array('data' => array('method' => 'GET', 'url' => $rootUrl.'/modify/'.$categoryId)),
            array('data' => array('method' => 'POST', 'url' => $rootUrl.'/delete/'.$categoryId)),
        );
    }
}
