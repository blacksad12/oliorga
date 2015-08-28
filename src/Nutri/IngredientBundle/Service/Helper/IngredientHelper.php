<?php
namespace Nutri\IngredientBundle\Service\Helper;

use Nutri\IngredientBundle\Entity\Ingredient;

class IngredientHelper
{   
    /** ************************************************************************
     * For each Ingredient of the array $ingredientArray, give the intakes quantities (in gram)
     * [ingredient_id] => [
     *     'ingredient' => \Nutri\RecipeBundle\Entity\Ingredient
     *     'energyKcal' => float (this Ingredient will give xx Kcal, given the quantities in the Menu)
     *     'fat'        => float (this Ingredient will give xx gram of fat, given the quantities in the Menu)
     *     etc.
     * ]
     * @param array $ingredientArray
     * @return array
     **************************************************************************/
    public function getIntakeQuantityForIngredients(array $ingredientArray) {
        $intakeArray = array();
        foreach ($ingredientArray as $ingredientId=>$ingredientList) {
            $ingredient = $ingredientList['ingredient'];
            $quantityInGram = $ingredientList['quantity'];
            if($ingredientList['unit'] === \Nutri\IngredientBundle\Entity\Ingredient::UNIT_GRAM) {
                $quantityInGram = $ingredientList['quantity'];
            } elseif($ingredientList['unit'] === \Nutri\IngredientBundle\Entity\Ingredient::UNIT_CENTILITER) { // Centiliter
                $quantityInGram = $ingredientList['quantity'] * 10; // Approximation: 1L = 1Kg
            }
            
            $intakeArray[$ingredientId]['ingredient']   = $ingredient;
            $intakeArray[$ingredientId]['quantity']     = $ingredientList['quantity'];
            $intakeArray[$ingredientId]['unit']         = $ingredientList['unit'];
            $intakeArray[$ingredientId]['energyKcal']   = $ingredient->getEnergyKcal() * $quantityInGram / 100;
            $intakeArray[$ingredientId]['fat']          = $ingredient->getFat() * $quantityInGram / 100;
            $intakeArray[$ingredientId]['saturatedFat'] = $ingredient->getSaturatedFat() * $quantityInGram / 100;
            $intakeArray[$ingredientId]['carbohydrate'] = $ingredient->getCarbohydrate() * $quantityInGram / 100;
            $intakeArray[$ingredientId]['sugars']       = $ingredient->getSugars() * $quantityInGram / 100;
            $intakeArray[$ingredientId]['fiber']        = $ingredient->getFiber() * $quantityInGram / 100;
            $intakeArray[$ingredientId]['proteins']     = $ingredient->getProteins() * $quantityInGram / 100;
            $intakeArray[$ingredientId]['salt']         = $ingredient->getSalt() * $quantityInGram / 100;
            $intakeArray[$ingredientId]['sodium']       = $ingredient->getSodium() * $quantityInGram / 100;
        }
        return $intakeArray;
    }
    
}
