<?php

namespace Finance\OperationBundle\Tests\Controller;

use Oliorga\CoreBundle\Tests\Controller\AbstractControllerTest;

class TransferBetweenAccountControllerTest extends AbstractControllerTest
{
    /** ************************************************************************
     * Return an array containing one array per Controller's action, with the 
     * route data.
     * @return array
     **************************************************************************/
    public function getRouteData() {
        $transferbetweenaccountId = 1;
        $rootUrl = '/operation/transferbetweenaccount';        
        return array(
            array('data' => array('method' => 'GET', 'url' => $rootUrl.'/')),
            array('data' => array('method' => 'GET', 'url' => $rootUrl.'/add')),
            array('data' => array('method' => 'GET', 'url' => $rootUrl.'/see/'.$transferbetweenaccountId)),
            array('data' => array('method' => 'GET', 'url' => $rootUrl.'/modify/'.$transferbetweenaccountId)),
            array('data' => array('method' => 'POST', 'url' => $rootUrl.'/delete/'.$transferbetweenaccountId)),
        );
    }
}
