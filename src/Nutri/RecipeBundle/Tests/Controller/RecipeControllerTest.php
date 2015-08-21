<?php

namespace Nutri\RecipeBundle\Tests\Controller;

use Oliorga\CoreBundle\Tests\Controller\AbstractControllerTest;

class RecipeControllerTest extends AbstractControllerTest
{
    /** ************************************************************************
     * Return an array containing one array per Controller's action, with the 
     * route data.
     * @return array
     **************************************************************************/
    public function getRouteData() {
        $recipeId = 1;
        $rootUrl = '/recipe/recipe';        
        return array(
            array('data' => array('method' => 'GET', 'url' => $rootUrl.'/')),
            array('data' => array('method' => 'GET', 'url' => $rootUrl.'/add')),
            array('data' => array('method' => 'GET', 'url' => $rootUrl.'/see/'.$recipeId)),
            array('data' => array('method' => 'GET', 'url' => $rootUrl.'/modify/'.$recipeId)),
            array('data' => array('method' => 'POST', 'url' => $rootUrl.'/delete/'.$recipeId)),
        );
    }
}
