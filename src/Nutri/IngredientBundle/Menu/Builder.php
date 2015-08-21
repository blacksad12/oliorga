<?php
namespace Nutri\IngredientBundle\Menu;

use Knp\Menu\FactoryInterface;
use Knp\Menu\MenuItem;
use Symfony\Component\DependencyInjection\ContainerAware;

class Builder extends ContainerAware
{
    /** ************************************************************************
     * Get the main menu's item related to the Equipments.
     * 
     * @param \Knp\Menu\FactoryInterface $factory
     * @param array $options
     * @return Knp\Menu\MenuItem
     **************************************************************************/
    public function mainMenu(FactoryInterface $factory, array $options)
    {
        $menu = $factory->createItem('root');
        $menu->setChildrenAttribute('class', 'nav navbar-nav');

        $menu->addChild('Ingredient')->setAttribute('dropdown', true);
        
        $this->addChildIngredient($menu['Ingredient']);
        
        return $menu;
    }
    
    /** ************************************************************************
     * Add menu child "Ingredient" to $menu
     * @param \Knp\Menu\MenuItem $menu
     **************************************************************************/
    protected function addChildIngredient(MenuItem $menu) {
        $menu->addChild('Ingredient')->setAttribute('dropdown', true);
        $menu['Ingredient']->addChild('Home', array('route' => 'nutri_ingredient_ingredient_home'))->setAttribute('icon', 'glyphicon-list');
        $menu['Ingredient']->addChild('New', array('route' => 'nutri_ingredient_ingredient_add'))->setAttribute('icon', 'glyphicon-plus');        
    }
      
    
}