<?php
namespace Nutri\IngredientBundle\Service\Importer;

use Nutri\IngredientBundle\Entity\Ingredient;

class OpenfoodfactIngredientImporter
{        
    private $em;
    private $doctrine;
    
    /** ************************************************************************
     * Constructor
     * @param type $doctrine
     * @param \Doctrine\ORM\EntityManager $em
     **************************************************************************/
    public function __construct($doctrine, \Doctrine\ORM\EntityManager $em) {
        $this->doctrine = $doctrine;
        $this->em       = $em;
    }
    
    /** ************************************************************************
     * Import all.
     **************************************************************************/
    public function import() {
        $openfoodfactIngredientArray = $this->getOpenfoodfactIngredientArray();
        foreach($openfoodfactIngredientArray as $key=>$openfoodfactIngredient) {
            $existingOpenfoodfactIngredient = $this->doctrine
                ->getRepository('NutriIngredientBundle:Ingredient')
                ->findOneByBarcode($openfoodfactIngredient['code']);
            
            $ingredient = $existingOpenfoodfactIngredient !== NULL ? $existingOpenfoodfactIngredient : new Ingredient();
            $this->setOpenfoodfactValues($ingredient, $openfoodfactIngredient);
            $this->em->persist($ingredient);
        }
        $this->em->flush();
    }
    
    /** ************************************************************************
     * Return true if the $openfoodfactIngredient has enough data to be usefull.
     * @param array $openfoodfactIngredient
     * @return boolean
     **************************************************************************/
    protected function isOpenfoodfactIngredientInterresting(array $openfoodfactIngredient) {
        if(     !(strpos($openfoodfactIngredient['countries_tags'],'en:france') !== false)
                || $openfoodfactIngredient['product_name'] === NULL || $openfoodfactIngredient['product_name'] === ''
                || $openfoodfactIngredient['brands'] === NULL || $openfoodfactIngredient['brands'] === ''
                || $openfoodfactIngredient['energy_100g'] === NULL || $openfoodfactIngredient['energy_100g'] === ''
            ) {
            return false;
        }        
        return true;
    }
    
    /** ************************************************************************
     * Get a kay=>value array of all the OpenFoodFact Ingredients
     * Filter the raw CSV file as follows:
     * - Keep only the columns :
     *   - code
     *   - product_name   (filter: not empty)
     *   - brands         (filter: not empty)
     *   - countries_tags (filter: contains 'en:france')
     *   - energy_100g    (filter: not empty)
     *   - fat_100g	
     *   - saturated-fat_100g
     *   - carbohydrates_100g
     *   - sugars_100g
     *   - fiber_100g
     *   - proteins_100g
     *   - salt_100g
     *   - sodium_100g
     * - Remove '&quot;' chains
     * - Replace ';' by '-'
     * - File must be UTF8 (check if accent are OK in MySQL DB)
     * @return array
     **************************************************************************/
    protected function getOpenfoodfactIngredientArray() {
        $csvRaw = file_get_contents('import/fr.openfoodfacts.org.products_filtered.csv');
        $csvLines = explode(PHP_EOL, $csvRaw);
        $headerArray = explode(';',$csvLines[0]);
        unset($csvLines[0]);
        $openfoodfactIngredients = array();
        foreach($csvLines as $keyLine=>$csvLine) {
            $csvFields = explode(';', $csvLine);
            $openfoodfactIngredients[$keyLine] = array();
            foreach($csvFields as $keyField=>$csvField) {
                $openfoodfactIngredients[$keyLine][rtrim($headerArray[$keyField])] = rtrim($csvField);
            }
        }
        return $openfoodfactIngredients;
    }
    
    /** ************************************************************************
     * Set to $ingredient the values of the $openfoodfactIngredient
     * @param Ingredient $ingredient
     * @param array $openfoodfactIngredient
     **************************************************************************/
    protected function setOpenfoodfactValues(Ingredient $ingredient, array $openfoodfactIngredient) {
        $ingredient->setName($openfoodfactIngredient['product_name'].' ('.$openfoodfactIngredient['brands'].')');
        $ingredient->setBarcode($openfoodfactIngredient['code']);
        $ingredient->setEnergyKcal(intval($openfoodfactIngredient['energy_100g'])/4.184); // 'energy_100g' is in kj
        $ingredient->setFat($this->strToFloat($openfoodfactIngredient['fat_100g']));
        $ingredient->setSaturatedFat($this->strToFloat($openfoodfactIngredient['saturated-fat_100g']));
        $ingredient->setCarbohydrate($this->strToFloat($openfoodfactIngredient['carbohydrates_100g']));
        $ingredient->setSugars($this->strToFloat($openfoodfactIngredient['sugars_100g']));
        $ingredient->setFiber($this->strToFloat($openfoodfactIngredient['fiber_100g']));
        $ingredient->setProteins($this->strToFloat($openfoodfactIngredient['proteins_100g']));
        $ingredient->setSalt($this->strToFloat($openfoodfactIngredient['salt_100g']));
        $ingredient->setSodium($this->strToFloat($openfoodfactIngredient['sodium_100g'])); 
    }
    
    /** ************************************************************************
     * Converts the string $floatAsString to a float (or NULL if data is not valid)
     * @param string $floatAsString
     * @return float
     **************************************************************************/
    protected function strToFloat($floatAsString) {
        if($floatAsString === '-' or $floatAsString === ''){
            return NULL;
        }
        $floatAsString = str_replace(',', '.', $floatAsString);
        return floatval($floatAsString);
    }
    
}
