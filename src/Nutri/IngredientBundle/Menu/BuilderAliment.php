<?php

namespace Nutri\IngredientBundle\Menu;

use Knp\Menu\FactoryInterface;
use Knp\Menu\MenuItem;
use Symfony\Component\DependencyInjection\ContainerAware;


class BuilderAliment extends ContainerAware
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
//        $this->addChildModify($menu, $options);
//        $this->addChildDelete($menu, $options);
        
        return $menu;
    }
    
    /** ************************************************************************
     * Add menu child "List" to $menu
     * @param \Knp\Menu\MenuItem $menu
     **************************************************************************/
    protected function addChildList(MenuItem $menu) {
        $menu->addChild('List', array('route' => 'nutri_ingredient_aliment_home'))->setAttribute('icon', 'glyphicon-list');
    }
    
    /** ************************************************************************
     * Add menu child "New" to $menu
     * @param \Knp\Menu\MenuItem $menu
     **************************************************************************/
    protected function addChildNew(MenuItem $menu) {
        $menu->addChild('New Aliment', array('route' => 'nutri_ingredient_aliment_add'))->setAttribute('icon', 'glyphicon-plus');
    }
    
    /** ************************************************************************
     * Add menu child "Modify" to $menu
     * @param \Knp\Menu\MenuItem $menu
     * @param array $options
     **************************************************************************/
    protected function addChildModify(MenuItem $menu, array $options) {
        $menu->addChild('Modify', array(
                    'route' => 'nutri_ingredient_aliment_modify', 
                    'routeParameters' => array('aliment_id' => $options['aliment']->getId())))
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
            ->setLinkAttribute('data-href', $this->container->get('router')->generate('nutri_ingredient_aliment_delete', array('aliment_id' => $options['aliment']->getId())))
            ;
    }
    
}
