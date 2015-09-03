<?php

namespace Finance\OperationBundle\Tests\Controller;

use Oliorga\CoreBundle\Tests\Controller\AbstractControllerTest;

class StakeholderControllerTest extends AbstractControllerTest
{
    /** ************************************************************************
     * Return an array containing one array per Controller's action, with the 
     * route data.
     * @return array
     **************************************************************************/
    public function getRouteData() {
        $stakeholderId = 1;
        $rootUrl = '/operation/stakeholder';        
        return array(
            array('data' => array('method' => 'GET', 'url' => $rootUrl.'/')),
            array('data' => array('method' => 'GET', 'url' => $rootUrl.'/add')),
            array('data' => array('method' => 'GET', 'url' => $rootUrl.'/see/'.$stakeholderId)),
            array('data' => array('method' => 'GET', 'url' => $rootUrl.'/modify/'.$stakeholderId)),
            array('data' => array('method' => 'POST', 'url' => $rootUrl.'/delete/'.$stakeholderId)),
        );
    }
}
