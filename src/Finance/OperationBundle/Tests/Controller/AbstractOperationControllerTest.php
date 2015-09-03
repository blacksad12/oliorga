<?php

namespace Finance\OperationBundle\Tests\Controller;

use Oliorga\CoreBundle\Tests\Controller\AbstractControllerTest;

class AbstractOperationControllerTest extends AbstractControllerTest
{
    /** ************************************************************************
     * Return an array containing one array per Controller's action, with the 
     * route data.
     * @return array
     **************************************************************************/
    public function getRouteData() {
        $abstractoperationId = 1;
        $rootUrl = '/operation/abstractoperation';        
        return array(
            array('data' => array('method' => 'GET', 'url' => $rootUrl.'/')),
            array('data' => array('method' => 'GET', 'url' => $rootUrl.'/add')),
            array('data' => array('method' => 'GET', 'url' => $rootUrl.'/see/'.$abstractoperationId)),
            array('data' => array('method' => 'GET', 'url' => $rootUrl.'/modify/'.$abstractoperationId)),
            array('data' => array('method' => 'POST', 'url' => $rootUrl.'/delete/'.$abstractoperationId)),
        );
    }
}
