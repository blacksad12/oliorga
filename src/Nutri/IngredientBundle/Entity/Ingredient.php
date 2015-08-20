<?php

namespace Nutri\IngredientBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Ingredient
 *
 * @ORM\Table(name="nutri__ingredient__ingredient")
 * @ORM\Entity(repositoryClass="Nutri\IngredientBundle\Entity\IngredientRepository")
 */
class Ingredient
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255, nullable=false)
     * @Assert\NotBlank(message="This value is mandatory: name")
     * @Assert\Length(max=255)
     */
    private $name;

    /**
     * @var integer
     *
     * @ORM\Column(name="barcode", type="integer", nullable=true)
     */
    private $barcode;

    /**
     * @var integer
     *
     * @ORM\Column(name="ciqualcode", type="integer", nullable=true)
     */
    private $ciqualcode;

    /**
     * @var integer
     *
     * @ORM\Column(name="energyKcal", type="integer", nullable=true)
     */
    private $energyKcal;

    /**
     * @var float
     *
     * @ORM\Column(name="fat", type="float", nullable=true)
     */
    private $fat;

    /**
     * @var float
     *
     * @ORM\Column(name="saturatedFat", type="float", nullable=true)
     */
    private $saturatedFat;

    /**
     * @var float
     *
     * @ORM\Column(name="carbohydrate", type="float", nullable=true)
     */
    private $carbohydrate;

    /**
     * @var float
     *
     * @ORM\Column(name="sugars", type="float", nullable=true)
     */
    private $sugars;

    /**
     * @var float
     *
     * @ORM\Column(name="fiber", type="float", nullable=true)
     */
    private $fiber;

    /**
     * @var float
     *
     * @ORM\Column(name="proteins", type="float", nullable=true)
     */
    private $proteins;

    /**
     * @var float
     *
     * @ORM\Column(name="salt", type="float", nullable=true)
     */
    private $salt;

    /**
     * @var float
     *
     * @ORM\Column(name="sodium", type="float", nullable=true)
     */
    private $sodium;


    /**************************************************************************/
    ////////////////////////////////////////////////////////////////////////////
    //                             To String
    ////////////////////////////////////////////////////////////////////////////
    public function __toString() {
        return $this->getName();
    }
    
    /**************************************************************************/
    ////////////////////////////////////////////////////////////////////////////
    //                  Custom setters and getters
    ////////////////////////////////////////////////////////////////////////////
    
    /**************************************************************************/
    ////////////////////////////////////////////////////////////////////////////
    //                  Attributes' setters and getters
    ////////////////////////////////////////////////////////////////////////////

    /**
     * Get id
     * @return integer 
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Set name
     * @param string $name
     * @return Ingredient
     */
    public function setName($name) {
        $this->name = $name;
        return $this;
    }

    /**
     * Get name
     * @return string 
     */
    public function getName() {
        return $this->name;
    }

    /**
     * Set barcode
     * @param integer $barcode
     * @return Ingredient
     */
    public function setBarcode($barcode) {
        $this->barcode = $barcode;
        return $this;
    }

    /**
     * Get barcode
     * @return integer 
     */
    public function getBarcode() {
        return $this->barcode;
    }

    /**
     * Set ciqualcode
     * @param integer $ciqualcode
     * @return Ingredient
     */
    public function setCiqualcode($ciqualcode) {
        $this->ciqualcode = $ciqualcode;
        return $this;
    }

    /**
     * Get ciqualcode
     * @return integer 
     */
    public function getCiqualcode() {
        return $this->ciqualcode;
    }

    /**
     * Set energyKcal
     * @param integer $energyKcal
     * @return Ingredient
     */
    public function setEnergyKcal($energyKcal) {
        $this->energyKcal = $energyKcal;
        return $this;
    }

    /**
     * Get energyKcal
     * @return integer 
     */
    public function getEnergyKcal() {
        return $this->energyKcal;
    }

    /**
     * Set fat
     * @param float $fat
     * @return Ingredient
     */
    public function setFat($fat) {
        $this->fat = $fat;
        return $this;
    }

    /**
     * Get fat
     * @return float 
     */
    public function getFat() {
        return $this->fat;
    }

    /**
     * Set saturatedFat
     * @param float $saturatedFat
     * @return Ingredient
     */
    public function setSaturatedFat($saturatedFat) {
        $this->saturatedFat = $saturatedFat;
        return $this;
    }

    /**
     * Get saturatedFat
     * @return float 
     */
    public function getSaturatedFat() {
        return $this->saturatedFat;
    }

    /**
     * Set carbohydrate
     * @param float $carbohydrate
     * @return Ingredient
     */
    public function setCarbohydrate($carbohydrate) {
        $this->carbohydrate = $carbohydrate;
        return $this;
    }

    /**
     * Get carbohydrate
     * @return float 
     */
    public function getCarbohydrate() {
        return $this->carbohydrate;
    }

    /**
     * Set sugars
     * @param float $sugars
     * @return Ingredient
     */
    public function setSugars($sugars) {
        $this->sugars = $sugars;
        return $this;
    }

    /**
     * Get sugars
     * @return float 
     */
    public function getSugars() {
        return $this->sugars;
    }

    /**
     * Set fiber
     * @param float $fiber
     * @return Ingredient
     */
    public function setFiber($fiber) {
        $this->fiber = $fiber;
        return $this;
    }

    /**
     * Get fiber
     * @return float 
     */
    public function getFiber() {
        return $this->fiber;
    }

    /**
     * Set proteins
     * @param float $proteins
     * @return Ingredient
     */
    public function setProteins($proteins) {
        $this->proteins = $proteins;
        return $this;
    }

    /**
     * Get proteins
     * @return float 
     */
    public function getProteins() {
        return $this->proteins;
    }

    /**
     * Set salt
     * @param float $salt
     * @return Ingredient
     */
    public function setSalt($salt) {
        $this->salt = $salt;
        return $this;
    }

    /**
     * Get salt
     * @return float 
     */
    public function getSalt() {
        return $this->salt;
    }

    /**
     * Set sodium
     * @param float $sodium
     * @return Ingredient
     */
    public function setSodium($sodium) {
        $this->sodium = $sodium;
        return $this;
    }

    /**
     * Get sodium
     * @return float 
     */
    public function getSodium() {
        return $this->sodium;
    }
}
