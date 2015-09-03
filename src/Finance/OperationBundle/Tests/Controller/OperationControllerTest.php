<?php

namespace Finance\OperationBundle\Tests\Controller;

use Oliorga\CoreBundle\Tests\Controller\AbstractControllerTest;

class OperationControllerTest extends AbstractControllerTest
{
    /** ************************************************************************
     * Return an array containing one array per Controller's action, with the 
     * route data.
     * @return array
     **************************************************************************/
    public function getRouteData() {
        $operationId = 1;
        $rootUrl = '/operation/operation';        
        return array(
            array('data' => array('method' => 'GET', 'url' => $rootUrl.'/')),
            array('data' => array('method' => 'GET', 'url' => $rootUrl.'/add')),
            array('data' => array('method' => 'GET', 'url' => $rootUrl.'/see/'.$operationId)),
            array('data' => array('method' => 'GET', 'url' => $rootUrl.'/modify/'.$operationId)),
            array('data' => array('method' => 'POST', 'url' => $rootUrl.'/delete/'.$operationId)),
        );
    }
}
