<?php
namespace Nutri\RecipeBundle\Service\Helper;

use Nutri\RecipeBundle\Entity\Shoplist;

class ShoplistHelper
{        
    private $em;
    private $doctrine;
    
    public function __construct($doctrine, \Doctrine\ORM\EntityManager $em) {
        $this->doctrine = $doctrine;
        $this->em       = $em;
    }
    
    
    public function getIngredientListWithQuantities(Shoplist $shoplist) {
        $ingredientList = array();
        
        foreach($shoplist->getIngredientsForShoplist() as $ingredientForShoplist) {
            if(array_key_exists($ingredientForShoplist->getIngredient()->getId(),$ingredientList)){
                $ingredientList[$ingredientForShoplist->getIngredient()->getId()]['quantity'] += $ingredientForShoplist->getQuantity();
            } else {
                $ingredientList[$ingredientForShoplist->getIngredient()->getId()]['ingredient'] = $ingredientForShoplist->getIngredient();
                $ingredientList[$ingredientForShoplist->getIngredient()->getId()]['unit'] = $ingredientForShoplist->getUnit();
                $ingredientList[$ingredientForShoplist->getIngredient()->getId()]['quantity'] = $ingredientForShoplist->getQuantity();
            }
        }
        
        foreach($shoplist->getRecipesForShoplist() as $recipeForShoplist) {
            foreach($recipeForShoplist->getRecipe()->getIngredientsForRecipe() as $ingredientForRecipe) {
                $quantity = $recipeForShoplist->getNbPeople() * $ingredientForRecipe->getQuantity() / $ingredientForRecipe->getRecipe()->getNbPeople();
                if(array_key_exists($ingredientForRecipe->getIngredient()->getId(),$ingredientList)){
                    $ingredientList[$ingredientForRecipe->getIngredient()->getId()]['quantity'] += $quantity;
                } else {
                    $ingredientList[$ingredientForRecipe->getIngredient()->getId()]['ingredient'] = $ingredientForRecipe->getIngredient();
                    $ingredientList[$ingredientForRecipe->getIngredient()->getId()]['unit'] = $ingredientForRecipe->getUnit();
                    $ingredientList[$ingredientForRecipe->getIngredient()->getId()]['quantity'] = $quantity;
                }
            }
        
        }
        return $ingredientList;
    }
    
    
}
