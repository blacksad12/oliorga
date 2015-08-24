<?php
namespace Nutri\RecipeBundle\Service\Helper;

use Nutri\RecipeBundle\Entity\Person;

class PersonHelper
{        
    /** ************************************************************************
     * Get the default Reference Daily Intake values
     * @return array
     **************************************************************************/
    public function getDefaultReferenceDailyIntake() {
        return array(
            'energyKcal'    => 2000,
            'fat'           => 70,
            'saturatedFat'  => 20,
            'carbohydrate'  => 260,
            'sugars'        => 90,
            'fiber'         => 25,
            'proteins'      => 50,
            'salt'          => 6,
            'sodium'        => 6/2.5,
        );
    }
    
    /** ************************************************************************
     * Get the Reference Dail Intake of the Person, based on the Default RDI
     * weighted by the Person's real metabolic needs.
     * @param Person $person
     * @return array
     **************************************************************************/
    public function getReferenceDailyIntakeArray(Person $person) {
        $defaultRDI = $this->getDefaultReferenceDailyIntake();
        $ratioPersonToDefault = $person->getKcalNeeded() / $defaultRDI['energyKcal'];
        $referenceDailyIntake = array(
            'energyKcal'    => $person->getKcalNeeded(),
            'fat'           => $defaultRDI['fat'] * $ratioPersonToDefault,
            'saturatedFat'  => $defaultRDI['saturatedFat'] * $ratioPersonToDefault,
            'carbohydrate'  => $defaultRDI['carbohydrate'] * $ratioPersonToDefault,
            'sugars'        => $defaultRDI['sugars'] * $ratioPersonToDefault,
            'fiber'         => $defaultRDI['fiber'] * $ratioPersonToDefault,
            'proteins'      => $defaultRDI['proteins'] * $ratioPersonToDefault,
            'salt'          => $defaultRDI['salt'] * $ratioPersonToDefault,
            'sodium'        => $defaultRDI['sodium'] * $ratioPersonToDefault,
        );        
        return $referenceDailyIntake;
    }
    
}
