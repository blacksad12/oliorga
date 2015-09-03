<?php

namespace Finance\OperationBundle\Tests\Controller;

use Oliorga\CoreBundle\Tests\Controller\AbstractControllerTest;

class ImputationControllerTest extends AbstractControllerTest
{
    /** ************************************************************************
     * Return an array containing one array per Controller's action, with the 
     * route data.
     * @return array
     **************************************************************************/
    public function getRouteData() {
        $imputationId = 1;
        $rootUrl = '/operation/imputation';        
        return array(
            array('data' => array('method' => 'GET', 'url' => $rootUrl.'/')),
            array('data' => array('method' => 'GET', 'url' => $rootUrl.'/add')),
            array('data' => array('method' => 'GET', 'url' => $rootUrl.'/see/'.$imputationId)),
            array('data' => array('method' => 'GET', 'url' => $rootUrl.'/modify/'.$imputationId)),
            array('data' => array('method' => 'POST', 'url' => $rootUrl.'/delete/'.$imputationId)),
        );
    }
}
