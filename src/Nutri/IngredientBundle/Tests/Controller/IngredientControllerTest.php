<?php

namespace Nutri\IngredientBundle\Tests\Controller;

use Oliorga\CoreBundle\Tests\Controller\AbstractControllerTest;

class IngredientControllerTest extends AbstractControllerTest
{
    /** ************************************************************************
     * Return an array containing one array per Controller's action, with the 
     * route data.
     * @return array
     **************************************************************************/
    public function getRouteData() {
        $ingredientId = 1;
        $rootUrl = '/ingredient/ingredient';        
        return array(
            array('data' => array('method' => 'GET', 'url' => $rootUrl.'/')),
            array('data' => array('method' => 'GET', 'url' => $rootUrl.'/add')),
            array('data' => array('method' => 'GET', 'url' => $rootUrl.'/see/'.$ingredientId)),
            array('data' => array('method' => 'GET', 'url' => $rootUrl.'/modify/'.$ingredientId)),
            array('data' => array('method' => 'POST', 'url' => $rootUrl.'/delete/'.$ingredientId)),
        );
    }
}
