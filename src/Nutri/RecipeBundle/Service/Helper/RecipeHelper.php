<?php
namespace Nutri\RecipeBundle\Service\Helper;

use Nutri\RecipeBundle\Entity\Recipe;

class RecipeHelper
{        
    private $em;
    private $personHelper;
    
    public function __construct(\Doctrine\ORM\EntityManager $em, \Nutri\RecipeBundle\Service\Helper\PersonHelper $personHelper) {
        $this->em       = $em;
        $this->personHelper = $personHelper;
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
