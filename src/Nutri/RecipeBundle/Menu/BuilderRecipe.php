<?php

namespace Nutri\RecipeBundle\Menu;

use Knp\Menu\FactoryInterface;
use Knp\Menu\MenuItem;
use Symfony\Component\DependencyInjection\ContainerAware;


class BuilderRecipe extends ContainerAware
{
    /** ************************************************************************
     * Get the sub-menu displayed at the top of the "see" view.
     * 
     * @param \Knp\Menu\FactoryInterface $factory
     * @param array $options
     * @return Knp\Menu\MenuItem
     **************************************************************************/
    public function seeMenu(FactoryInterface $factory, array $options) {
        $menu = $factory->createItem('root');
        
        $this->addChildList($menu);
        $this->addChildNew($menu);
        $this->addChildModify($menu, $options);
        $this->addChildDelete($menu, $options);
        
        return $menu;
    }
    
    /** ************************************************************************
     * Add menu child "List" to $menu
     * @param \Knp\Menu\MenuItem $menu
     **************************************************************************/
    protected function addChildList(MenuItem $menu) {
        $menu->addChild('List', array('route' => 'nutri_recipe_recipe_home'))->setAttribute('icon', 'glyphicon-list');
    }
    
    /** ************************************************************************
     * Add menu child "New" to $menu
     * @param \Knp\Menu\MenuItem $menu
     **************************************************************************/
    protected function addChildNew(MenuItem $menu) {
        $menu->addChild('New Recipe', array('route' => 'nutri_recipe_recipe_add'))->setAttribute('icon', 'glyphicon-plus');
    }
    
    /** ************************************************************************
     * Add menu child "Modify" to $menu
     * @param \Knp\Menu\MenuItem $menu
     * @param array $options
     **************************************************************************/
    protected function addChildModify(MenuItem $menu, array $options) {
        $menu->addChild('Modify', array(
                    'route' => 'nutri_recipe_recipe_modify', 
                    'routeParameters' => array('recipe_id' => $options['recipe']->getId())))
             ->setAttribute('icon', 'glyphicon-edit');
    }
    
    /** ************************************************************************
     * Add menu child "Delete" to $menu
     * @param \Knp\Menu\MenuItem $menu
     * @param array $options
     **************************************************************************/
    protected function addChildDelete(MenuItem $menu, array $options) {
        $menu->addChild('Delete', array('uri' => '#'))
            ->setAttribute('icon', 'glyphicon-trash')
            ->setLinkAttribute('data-toggle', 'modal')
            ->setLinkAttribute('data-target', '#confirm-delete')
            ->setLinkAttribute('data-href', $this->container->get('router')->generate('nutri_recipe_recipe_delete', array('recipe_id' => $options['recipe']->getId())))
            ;
    }
    
}
