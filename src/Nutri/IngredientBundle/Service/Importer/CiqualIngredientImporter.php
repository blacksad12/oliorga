<?php
namespace Nutri\IngredientBundle\Service\Importer;

use Nutri\IngredientBundle\Entity\Ingredient;

class CiqualIngredientImporter
{        
    private $em;
    private $doctrine;
    
    public function __construct($doctrine, \Doctrine\ORM\EntityManager $em) {
        $this->doctrine = $doctrine;
        $this->em       = $em;
    }
    
    
    public function import() {
        $ciqualIngredientArray = $this->getCiqualIngredientArray();
        foreach($ciqualIngredientArray as $ciqualIngredient) {
            $existingCiqualIngredient = $this->doctrine
                ->getRepository('NutriIngredientBundle:Ingredient')
                ->findOneByCiqualcode($ciqualIngredient['ORIGFDCD']);
            
            $ingredient = $existingCiqualIngredient !== NULL ? $existingCiqualIngredient : new Ingredient();
            $this->setCiqualValues($ingredient, $ciqualIngredient);
            $this->em->persist($ingredient);
        }
        $this->em->flush();
    }
    
    protected function getCiqualIngredientArray() {
        $csvRaw = file_get_contents('import/CIQUAL2013.csv');
        $csvLines = explode(PHP_EOL, $csvRaw);
        $headerArray = explode(';',$csvLines[0]);
        unset($csvLines[0]);
        $ciqualIngredients = array();
        foreach($csvLines as $keyLine=>$csvLine) {
            $csvFields = explode(';', $csvLine);
            $ciqualIngredients[$keyLine] = array();
            foreach($csvFields as $keyField=>$csvField) {
                $ciqualIngredients[$keyLine][$headerArray[$keyField]] = $csvField;
            }
        }
        return $ciqualIngredients;
    }
    
    protected function setCiqualValues(Ingredient $ingredient, array $ciqualIngredient) {
        $ingredient->setName($ciqualIngredient['ORIGFDNM']);
        $ingredient->setCiqualcode(intval($ciqualIngredient['ORIGFDCD']));
        $ingredient->setEnergyKcal(intval($ciqualIngredient['328 Energie, Règlement UE N° 1169/2011 (kcal/100g)']));
        $ingredient->setFat($this->strToFloat($ciqualIngredient['40000 Lipides (g/100g)']));
        $ingredient->setSaturatedFat($this->strToFloat($ciqualIngredient['40302 AG saturés (g/100g)']));
        $ingredient->setCarbohydrate($this->strToFloat($ciqualIngredient['31000 Glucides (g/100g)']));
        $ingredient->setSugars($this->strToFloat($ciqualIngredient['32000 Sucres (g/100g)']));
        $ingredient->setFiber($this->strToFloat($ciqualIngredient['34100 Fibres (g/100g)']));
        $ingredient->setProteins($this->strToFloat($ciqualIngredient['25000 Protéines (g/100g)']));
        $ingredient->setSalt($this->strToFloat($ciqualIngredient['10110 Sodium (mg/100g)'])*2.5);
        $ingredient->setSodium($this->strToFloat($ciqualIngredient['10110 Sodium (mg/100g)'])); 
    }
    
    protected function strToFloat($floatAsString) {
        if($floatAsString === '-' or $floatAsString === ''){
            return NULL;
        }
        $floatAsString = str_replace(',', '.', $floatAsString);
        return floatval($floatAsString);
    }
    
}
