<?php

namespace Nutri\RecipeBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Recipe
 *
 * @ORM\Table(name="nutri__recipe__recipe")
 * @ORM\Entity(repositoryClass="Nutri\RecipeBundle\Entity\RecipeRepository")
 */
class Recipe
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
     * @ORM\Column(name="nbPeople", type="integer", nullable=true)
     */
    private $nbPeople;

    /**
     * @var integer
     *
     * @ORM\Column(name="preparationTime", type="integer", nullable=true)
     */
    private $preparationTime;

    /**
     * @var integer
     *
     * @ORM\Column(name="cookingTime", type="integer", nullable=true)
     */
    private $cookingTime;

    /**
     * @var integer
     *
     * @ORM\Column(name="cookingTemperature", type="integer", nullable=true)
     */
    private $cookingTemperature;

    /**
     * @var string
     *
     * @ORM\Column(name="detail", type="text", nullable=true)
     */
    private $detail;
    
    /**
     * Ingredients used in this Recipe
     * @var Nutri\RecipeBundle\Entity\IngredientForRecipe[]
     * 
     * @ORM\OneToMany(targetEntity="Nutri\RecipeBundle\Entity\IngredientForRecipe", mappedBy="recipe", cascade={"persist"})
     */
    private $ingredientsForRecipe;

    /**************************************************************************/
    ////////////////////////////////////////////////////////////////////////////
    //                             Constructor
    ////////////////////////////////////////////////////////////////////////////
    public function __construct() {
        $this->ingredientsForRecipe = new \Doctrine\Common\Collections\ArrayCollection();
    }
    
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
     * @return Recipe
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
     * Set preparationTime
     * @param integer $preparationTime
     * @return Recipe
     */
    public function setPreparationTime($preparationTime) {
        $this->preparationTime = $preparationTime;
        return $this;
    }

    /**
     * Get preparationTime
     * @return integer 
     */
    public function getPreparationTime() {
        return $this->preparationTime;
    }

    /**
     * Set cookingTime
     * @param integer $cookingTime
     * @return Recipe
     */
    public function setCookingTime($cookingTime) {
        $this->cookingTime = $cookingTime;
        return $this;
    }

    /**
     * Get cookingTime
     * @return integer 
     */
    public function getCookingTime() {
        return $this->cookingTime;
    }

    /**
     * Set cookingTemperature
     * @param integer $cookingTemperature
     * @return Recipe
     */
    public function setCookingTemperature($cookingTemperature) {
        $this->cookingTemperature = $cookingTemperature;
        return $this;
    }

    /**
     * Get cookingTemperature
     * @return integer 
     */
    public function getCookingTemperature() {
        return $this->cookingTemperature;
    }

    /**
     * Set detail
     * @param string $detail
     * @return Recipe
     */
    public function setDetail($detail) {
        $this->detail = $detail;
        return $this;
    }

    /**
     * Get detail
     * @return string 
     */
    public function getDetail() {
        return $this->detail;
    }

    /**
     * Set nbPeople
     *
     * @param integer $nbPeople
     * @return Recipe
     */
    public function setNbPeople($nbPeople)
    {
        $this->nbPeople = $nbPeople;

        return $this;
    }

    /**
     * Get nbPeople
     *
     * @return integer 
     */
    public function getNbPeople()
    {
        return $this->nbPeople;
    }
        
    /**
     * Add ingredientsForRecipe
     *
     * @param \Nutri\RecipeBundle\Entity\IngredientForRecipe $ingredientForRecipe
     * @return Recipe
     */
    public function addIngredientForRecipe(\Nutri\RecipeBundle\Entity\IngredientForRecipe $ingredientForRecipe)
    {
        $this->ingredientsForRecipe[] = $ingredientForRecipe;

        return $this;
    }

    /**
     * Remove ingredientsForRecipe
     *
     * @param \Nutri\RecipeBundle\Entity\IngredientForRecipe $ingredientForRecipe
     */
    public function removeIngredientForRecipe(\Nutri\RecipeBundle\Entity\IngredientForRecipe $ingredientForRecipe)
    {
        $this->ingredientsForRecipe->removeElement($ingredientForRecipe);
    }

    /**
     * Get ingredientsForRecipe
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getIngredientsForRecipe()
    {
        return $this->ingredientsForRecipe;
    }
}
