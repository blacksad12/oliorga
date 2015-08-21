<?php

namespace Nutri\RecipeBundle\Tests\Controller;

use Oliorga\CoreBundle\Tests\Controller\AbstractControllerTest;

class ShoplistControllerTest extends AbstractControllerTest
{
    /** ************************************************************************
     * Return an array containing one array per Controller's action, with the 
     * route data.
     * @return array
     **************************************************************************/
    public function getRouteData() {
        $shoplistId = 1;
        $rootUrl = '/recipe/shoplist';        
        return array(
            array('data' => array('method' => 'GET', 'url' => $rootUrl.'/')),
            array('data' => array('method' => 'GET', 'url' => $rootUrl.'/add')),
            array('data' => array('method' => 'GET', 'url' => $rootUrl.'/see/'.$shoplistId)),
            array('data' => array('method' => 'GET', 'url' => $rootUrl.'/modify/'.$shoplistId)),
            array('data' => array('method' => 'POST', 'url' => $rootUrl.'/delete/'.$shoplistId)),
        );
    }
}
