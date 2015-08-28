<?php
namespace Nutri\RecipeBundle\Service\Helper;

use Nutri\RecipeBundle\Entity\Recipe;

class RecipeHelper
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
     * 
     * @param Recipe $recipe
     * @return array
     **************************************************************************/
    public function getReferenceDailyIntakePercentage(Recipe $recipe) {
        $persons = $this->em
                ->getRepository('NutriRecipeBundle:Person')
                ->findAll();
        $recipeIntakeArray = $this->getRecipeIntakePerPersonArray($recipe);
        $array = array();
        foreach($persons as $key=>$person) {
            $personIntakeArray = $this->personHelper->getReferenceDailyIntakeArray($person);
            $array[$key]['person'] = $person;
            $array[$key]['referenceDailyIntakePercentage'] = array(
                'energyKcal'    => $recipeIntakeArray['energyKcal'] * 100 / $personIntakeArray['energyKcal'],
                'fat'           => $recipeIntakeArray['fat'] * 100 / $personIntakeArray['fat'],
                'saturatedFat'  => $recipeIntakeArray['saturatedFat'] * 100 / $personIntakeArray['saturatedFat'],
                'carbohydrate'  => $recipeIntakeArray['carbohydrate'] * 100 / $personIntakeArray['carbohydrate'],
                'sugars'        => $recipeIntakeArray['sugars'] * 100 / $personIntakeArray['sugars'],
                'fiber'         => $recipeIntakeArray['fiber'] * 100 / $personIntakeArray['fiber'],
                'proteins'      => $recipeIntakeArray['proteins'] * 100 / $personIntakeArray['proteins'],
                'salt'          => $recipeIntakeArray['salt'] * 100 / $personIntakeArray['salt'],
                'sodium'        => $recipeIntakeArray['sodium'] * 100 / $personIntakeArray['sodium'],
            );
        }
        return $array;
    }
    
    /** ************************************************************************
     * Get an array of the Ingredients used in the Recipe $recipe
     * [ingredient_id] => [
     *     'ingredient' => \Nutri\RecipeBundle\Entity\Ingredient
     *     'unit'       => 'g' or 'cl'
     *     'quantity'   => integer
     * ]
     * @param Recipe $recipe
     * @return array
     **************************************************************************/
    public function getIngredientListWithQuantities(Recipe $recipe) {
        $ingredientList = array();
        
        foreach($recipe->getIngredientsForRecipe() as $ingredientForRecipe) {
            if(array_key_exists($ingredientForRecipe->getIngredient()->getId(),$ingredientList)){
                $ingredientList[$ingredientForRecipe->getIngredient()->getId()]['quantity']  += $ingredientForRecipe->getQuantity();
            } else {
                $ingredientList[$ingredientForRecipe->getIngredient()->getId()]['ingredient'] = $ingredientForRecipe->getIngredient();
                $ingredientList[$ingredientForRecipe->getIngredient()->getId()]['unit']       = $ingredientForRecipe->getUnit();
                $ingredientList[$ingredientForRecipe->getIngredient()->getId()]['quantity']   = $ingredientForRecipe->getQuantity();
            }
        }
        
        return $ingredientList;
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
     * @param Recipe $recipe
     * @return array
     **************************************************************************/
    public function getTotalIntakeQuantityAndPercentage(Recipe $recipe) {
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
        foreach($this->ingredientHelper->getIntakeQuantityForIngredients($this->getIngredientListWithQuantities($recipe)) as $ingredientIntakeArray) {
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
        
        $persons = $this->em
                ->getRepository('NutriRecipeBundle:Person')
                ->findAll();
        foreach($persons as $key=>$person) {
            $personIntakeArray = $this->personHelper->getReferenceDailyIntakeArray($person);
            $totalIntakeArray['percentRdi'][$key]['person']       = $person;
            $totalIntakeArray['percentRdi'][$key]['energyKcal']   = ($totalIntakeArray['absolute']['energyKcal'] / $recipe->getNbPeople()) * 100 / $personIntakeArray['energyKcal'];
            $totalIntakeArray['percentRdi'][$key]['fat']          = ($totalIntakeArray['absolute']['fat'] / $recipe->getNbPeople()) * 100 / $personIntakeArray['fat'];
            $totalIntakeArray['percentRdi'][$key]['saturatedFat'] = ($totalIntakeArray['absolute']['saturatedFat'] / $recipe->getNbPeople()) * 100 / $personIntakeArray['saturatedFat'];
            $totalIntakeArray['percentRdi'][$key]['carbohydrate'] = ($totalIntakeArray['absolute']['carbohydrate'] / $recipe->getNbPeople()) * 100 / $personIntakeArray['carbohydrate'];
            $totalIntakeArray['percentRdi'][$key]['sugars']       = ($totalIntakeArray['absolute']['sugars'] / $recipe->getNbPeople()) * 100 / $personIntakeArray['sugars'];
            $totalIntakeArray['percentRdi'][$key]['fiber']        = ($totalIntakeArray['absolute']['fiber'] / $recipe->getNbPeople()) * 100 / $personIntakeArray['fiber'];
            $totalIntakeArray['percentRdi'][$key]['proteins']     = ($totalIntakeArray['absolute']['proteins'] / $recipe->getNbPeople()) * 100 / $personIntakeArray['proteins'];
            $totalIntakeArray['percentRdi'][$key]['salt']         = ($totalIntakeArray['absolute']['salt'] / $recipe->getNbPeople()) * 100 / $personIntakeArray['salt'];
            $totalIntakeArray['percentRdi'][$key]['sodium']       = ($totalIntakeArray['absolute']['sodium'] / $recipe->getNbPeople()) * 100 / $personIntakeArray['sodium'];
        }
        
        return $totalIntakeArray;
    }
    
    /** ************************************************************************
     * 
     * @param Recipe $recipe
     * @return array
     **************************************************************************/
    public function getRecipeIntakePerPersonArray(Recipe $recipe) {
        $recipeIntakePerPersonArray = array(
            'energyKcal'    => 0,
            'fat'           => 0,
            'saturatedFat'  => 0,
            'carbohydrate'  => 0,
            'sugars'        => 0,
            'fiber'         => 0,
            'proteins'      => 0,
            'salt'          => 0,
            'sodium'        => 0,
        );
        
        $ingredientsForRecipe = $this->em
                ->getRepository('NutriRecipeBundle:IngredientForRecipe')
                ->findByRecipe($recipe);
        
        foreach($ingredientsForRecipe as $ingredientForRecipe) {
            $ingredient = $ingredientForRecipe->getIngredient();
            $quantityInGram = $ingredientForRecipe->getUnit();
            if($ingredientForRecipe->getUnit() === \Nutri\IngredientBundle\Entity\Ingredient::UNIT_GRAM) {
                $quantityInGram = $ingredientForRecipe->getQuantity();
            } else { // Centiliter
                $quantityInGram = $ingredientForRecipe->getQuantity() * 10; // Approximation: 1L = 1Kg
            }
            $hundredsOfGramPerPerson = ($quantityInGram / $recipe->getNbPeople()) / 100;
            
            $recipeIntakePerPersonArray['energyKcal']    += $ingredient->getEnergyKcal() * $hundredsOfGramPerPerson;
            $recipeIntakePerPersonArray['fat']           += $ingredient->getFat() * $hundredsOfGramPerPerson;
            $recipeIntakePerPersonArray['saturatedFat']  += $ingredient->getSaturatedFat() * $hundredsOfGramPerPerson;
            $recipeIntakePerPersonArray['carbohydrate']  += $ingredient->getCarbohydrate() * $hundredsOfGramPerPerson;
            $recipeIntakePerPersonArray['sugars']        += $ingredient->getSugars() * $hundredsOfGramPerPerson;
            $recipeIntakePerPersonArray['fiber']         += $ingredient->getFiber() * $hundredsOfGramPerPerson;
            $recipeIntakePerPersonArray['proteins']      += $ingredient->getProteins() * $hundredsOfGramPerPerson;
            $recipeIntakePerPersonArray['salt']          += $ingredient->getSalt() * $hundredsOfGramPerPerson;
            $recipeIntakePerPersonArray['sodium']        += $ingredient->getSodium() * $hundredsOfGramPerPerson;            
        }
        
        return $recipeIntakePerPersonArray;
    }
    
    
    
}
