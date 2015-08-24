<?php

namespace Nutri\RecipeBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * IngredientForRecipe
 *
 * @ORM\Table(name="nutri__recipe__ingredientforrecipe")
 * @ORM\Entity(repositoryClass="Nutri\RecipeBundle\Entity\IngredientForRecipeRepository")
 */
class IngredientForRecipe
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
     * Ingredient
     * @var Nutri\IngredientBundle\Entity\Ingredient
     * 
     * @Assert\NotBlank(message="This value is mandatory")
     * @ORM\ManyToOne(targetEntity="Nutri\IngredientBundle\Entity\Ingredient", inversedBy="ingredientForRecipes", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $ingredient;

    /**
     * Recipe
     * @var Nutri\RecipeBundle\Entity\Recipe
     * 
     * @Assert\NotBlank(message="This value is mandatory")
     * @ORM\ManyToOne(targetEntity="Nutri\RecipeBundle\Entity\Recipe", inversedBy="ingredientsForRecipe", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $recipe;

    /**
     * @var float
     *
     * @ORM\Column(name="quantity", type="float", nullable=false)
     * @Assert\NotBlank(message="This value is mandatory: quantity")
     */
    private $quantity;

    /**
     * @var string
     *
     * @ORM\Column(name="unit", type="string", length=50, nullable=false)
     * @Assert\Length(max=50)
     */
    private $unit;

    /**
     * @var string
     *
     * @ORM\Column(name="comment", type="string", length=255, nullable=true)
     * @Assert\Length(max=255)
     */
    private $comment;


    /**************************************************************************/
    ////////////////////////////////////////////////////////////////////////////
    //                             To String
    ////////////////////////////////////////////////////////////////////////////
    public function __toString() {
        return strval($this->getId());
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
     * Set quantity
     * @param float $quantity
     * @return IngredientForRecipe
     */
    public function setQuantity($quantity) {
        $this->quantity = $quantity;
        return $this;
    }

    /**
     * Get quantity
     * @return float 
     */
    public function getQuantity() {
        return $this->quantity;
    }

    /**
     * Set comment
     * @param string $comment
     * @return IngredientForRecipe
     */
    public function setComment($comment) {
        $this->comment = $comment;
        return $this;
    }

    /**
     * Get comment
     * @return string 
     */
    public function getComment() {
        return $this->comment;
    }

    /**
     * Set unit
     *
     * @param string $unit
     * @return IngredientForRecipe
     */
    public function setUnit($unit)
    {
        if (!in_array($unit, array(
                \Nutri\IngredientBundle\Entity\Ingredient::UNIT_GRAM, 
                \Nutri\IngredientBundle\Entity\Ingredient::UNIT_CENTILITER))) {
            throw new \InvalidArgumentException("Invalid 'unit' value");
        }
        
        $this->unit = $unit;

        return $this;
    }

    /**
     * Get unit
     *
     * @return string 
     */
    public function getUnit()
    {
        return $this->unit;
    }

    /**
     * Set ingredient
     *
     * @param \Nutri\IngredientBundle\Entity\Ingredient $ingredient
     * @return IngredientForRecipe
     */
    public function setIngredient(\Nutri\IngredientBundle\Entity\Ingredient $ingredient)
    {
        $this->ingredient = $ingredient;

        return $this;
    }

    /**
     * Get ingredient
     *
     * @return \Nutri\IngredientBundle\Entity\Ingredient 
     */
    public function getIngredient()
    {
        return $this->ingredient;
    }

    /**
     * Set recipe
     *
     * @param \Nutri\RecipeBundle\Entity\Recipe $recipe
     * @return IngredientForRecipe
     */
    public function setRecipe(\Nutri\RecipeBundle\Entity\Recipe $recipe)
    {
        $this->recipe = $recipe;

        return $this;
    }

    /**
     * Get recipe
     *
     * @return \Nutri\RecipeBundle\Entity\Recipe 
     */
    public function getRecipe()
    {
        return $this->recipe;
    }
}
