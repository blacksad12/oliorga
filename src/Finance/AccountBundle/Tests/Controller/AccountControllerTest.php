<?php

namespace Finance\AccountBundle\Tests\Controller;

use Oliorga\CoreBundle\Tests\Controller\AbstractControllerTest;

class AccountControllerTest extends AbstractControllerTest
{
    /** ************************************************************************
     * Return an array containing one array per Controller's action, with the 
     * route data.
     * @return array
     **************************************************************************/
    public function getRouteData() {
        $accountId = 1;
        $rootUrl = '/account/account';        
        return array(
            array('data' => array('method' => 'GET', 'url' => $rootUrl.'/')),
            array('data' => array('method' => 'GET', 'url' => $rootUrl.'/add')),
            array('data' => array('method' => 'GET', 'url' => $rootUrl.'/see/'.$accountId)),
            array('data' => array('method' => 'GET', 'url' => $rootUrl.'/modify/'.$accountId)),
            array('data' => array('method' => 'POST', 'url' => $rootUrl.'/delete/'.$accountId)),
        );
    }
}
