<?php
namespace Nutri\RecipeBundle\Service\Helper;

use Nutri\RecipeBundle\Entity\Menu;

class MenuHelper
{        
    private $em;
    private $personHelper;
    private $ingredientHelper;
    
    /** ************************************************************************
     * 
     * @param \Doctrine\ORM\EntityManager $em
     * @param \Nutri\RecipeBundle\Service\Helper\PersonHelper $personHelper
     **************************************************************************/
    public function __construct(
            \Doctrine\ORM\EntityManager $em, 
            \Nutri\RecipeBundle\Service\Helper\PersonHelper $personHelper,
            \Nutri\IngredientBundle\Service\Helper\IngredientHelper $ingredientHelper
            ) {
        $this->em       = $em;
        $this->personHelper = $personHelper;
        $this->ingredientHelper = $ingredientHelper;
    }
    
    /** ************************************************************************
     * Return the intakes in absolute and in percentage of ReferenceDailyIntake,
     * summed by Ingredient, for each type of intake.
     * ['absolute'] => [
     *     'energyKcal' => float (absolute value)
     *     'fat'        => float (absolute value)
     *     etc.
     * ]
     * ['percentRdi'] => [
     *     [person_id] => [
     *         'energyKcal' => float (percentage of Person's ReferenceDailyIntake)
     *         'fat'        => float (percentage of Person's ReferenceDailyIntake)
     *         etc.
     *     ]
     * ]
     * 
     * @param Menu $menu
     * @return array
     **************************************************************************/
    public function getTotalIntakeQuantityAndPercentage(Menu $menu) {
        $totalIntakeArray = array(
            'absolute' => array(
                'energyKcal' => 0,
                'fat' => 0,
                'saturatedFat' => 0,
                'carbohydrate' => 0,
                'sugars' => 0,
                'fiber' => 0,
                'proteins' => 0,
                'salt' => 0,
                'sodium' => 0,
                ), 
            'percentRdi' => array()
            );
        foreach($this->ingredientHelper->getIntakeQuantityForIngredients($this->getIngredientListWithQuantities($menu)) as $ingredientIntakeArray) {
            $totalIntakeArray['absolute']['energyKcal']     += $ingredientIntakeArray['energyKcal'];
            $totalIntakeArray['absolute']['fat']            += $ingredientIntakeArray['fat'];
            $totalIntakeArray['absolute']['saturatedFat']   += $ingredientIntakeArray['saturatedFat'];
            $totalIntakeArray['absolute']['carbohydrate']   += $ingredientIntakeArray['carbohydrate'];
            $totalIntakeArray['absolute']['sugars']         += $ingredientIntakeArray['sugars'];
            $totalIntakeArray['absolute']['fiber']          += $ingredientIntakeArray['fiber'];
            $totalIntakeArray['absolute']['proteins']       += $ingredientIntakeArray['proteins'];
            $totalIntakeArray['absolute']['salt']           += $ingredientIntakeArray['salt'];
            $totalIntakeArray['absolute']['sodium']         += $ingredientIntakeArray['sodium'];
        }
        $personIntakeArray = $this->personHelper->getReferenceDailyIntakeArray($menu->getPerson());
        $totalIntakeArray['percentRdi']['energyKcal']   = $totalIntakeArray['absolute']['energyKcal'] * 100 / $personIntakeArray['energyKcal'];
        $totalIntakeArray['percentRdi']['fat']          = $totalIntakeArray['absolute']['fat'] * 100 / $personIntakeArray['fat'];
        $totalIntakeArray['percentRdi']['saturatedFat'] = $totalIntakeArray['absolute']['saturatedFat'] * 100 / $personIntakeArray['saturatedFat'];
        $totalIntakeArray['percentRdi']['carbohydrate'] = $totalIntakeArray['absolute']['carbohydrate'] * 100 / $personIntakeArray['carbohydrate'];
        $totalIntakeArray['percentRdi']['sugars']       = $totalIntakeArray['absolute']['sugars'] * 100 / $personIntakeArray['sugars'];
        $totalIntakeArray['percentRdi']['fiber']        = $totalIntakeArray['absolute']['fiber'] * 100 / $personIntakeArray['fiber'];
        $totalIntakeArray['percentRdi']['proteins']     = $totalIntakeArray['absolute']['proteins'] * 100 / $personIntakeArray['proteins'];
        $totalIntakeArray['percentRdi']['salt']         = $totalIntakeArray['absolute']['salt'] * 100 / $personIntakeArray['salt'];
        $totalIntakeArray['percentRdi']['sodium']       = $totalIntakeArray['absolute']['sodium'] * 100 / $personIntakeArray['sodium'];
        return $totalIntakeArray;
    }
        
    /** ************************************************************************
     * Get an array of the Ingredients used in the Menu $menu
     * [ingredient_id] => [
     *     'ingredient' => \Nutri\RecipeBundle\Entity\Ingredient
     *     'unit'       => 'g' or 'cl'
     *     'quantity'   => integer
     * ]
     * @param Menu $menu
     * @return array
     **************************************************************************/
    public function getIngredientListWithQuantities(Menu $menu) {
        $ingredientList = array();
        
        foreach($menu->getIngredientsForMenu() as $ingredientForMenu) {
            if(array_key_exists($ingredientForMenu->getIngredient()->getId(),$ingredientList)){
                $ingredientList[$ingredientForMenu->getIngredient()->getId()]['quantity']  += $ingredientForMenu->getQuantity();
            } else {
                $ingredientList[$ingredientForMenu->getIngredient()->getId()]['ingredient'] = $ingredientForMenu->getIngredient();
                $ingredientList[$ingredientForMenu->getIngredient()->getId()]['unit']       = $ingredientForMenu->getUnit();
                $ingredientList[$ingredientForMenu->getIngredient()->getId()]['quantity']   = $ingredientForMenu->getQuantity();
            }
        }
        
        foreach($menu->getRecipes() as $recipe) {
            foreach($recipe->getIngredientsForRecipe() as $ingredientForRecipe) {
                $quantity = $ingredientForRecipe->getQuantity() / $ingredientForRecipe->getRecipe()->getNbPeople();
                if(array_key_exists($ingredientForRecipe->getIngredient()->getId(),$ingredientList)){
                    $ingredientList[$ingredientForRecipe->getIngredient()->getId()]['quantity']    += $quantity;
                } else {
                    $ingredientList[$ingredientForRecipe->getIngredient()->getId()]['ingredient']   = $ingredientForRecipe->getIngredient();
                    $ingredientList[$ingredientForRecipe->getIngredient()->getId()]['unit']         = $ingredientForRecipe->getUnit();
                    $ingredientList[$ingredientForRecipe->getIngredient()->getId()]['quantity']     = $quantity;
                }
            }
        }
        return $ingredientList;
    }
    
}
