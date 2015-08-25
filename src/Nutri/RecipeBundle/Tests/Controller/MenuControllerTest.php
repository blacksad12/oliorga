<?php

namespace Nutri\RecipeBundle\Tests\Controller;

use Oliorga\CoreBundle\Tests\Controller\AbstractControllerTest;

class MenuControllerTest extends AbstractControllerTest
{
    /** ************************************************************************
     * Return an array containing one array per Controller's action, with the 
     * route data.
     * @return array
     **************************************************************************/
    public function getRouteData() {
        $menuId = 1;
        $rootUrl = '/recipe/menu';        
        return array(
            array('data' => array('method' => 'GET', 'url' => $rootUrl.'/')),
            array('data' => array('method' => 'GET', 'url' => $rootUrl.'/add')),
            array('data' => array('method' => 'GET', 'url' => $rootUrl.'/see/'.$menuId)),
            array('data' => array('method' => 'GET', 'url' => $rootUrl.'/modify/'.$menuId)),
            array('data' => array('method' => 'POST', 'url' => $rootUrl.'/delete/'.$menuId)),
        );
    }
}
