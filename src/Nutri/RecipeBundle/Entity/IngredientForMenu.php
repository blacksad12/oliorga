<?php

namespace Nutri\RecipeBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * IngredientForMenu
 *
 * @ORM\Table(name="nutri__recipe__ingredientformenu")
 * @ORM\Entity(repositoryClass="Nutri\RecipeBundle\Entity\IngredientForMenuRepository")
 */
class IngredientForMenu
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
     * @ORM\ManyToOne(targetEntity="Nutri\IngredientBundle\Entity\Ingredient")
     * @ORM\JoinColumn(nullable=false)
     */
    private $ingredient;

    /**
     * Menu
     * @var Nutri\RecipeBundle\Entity\Menu
     * 
     * @Assert\NotBlank(message="This value is mandatory")
     * @ORM\ManyToOne(targetEntity="Nutri\RecipeBundle\Entity\Menu", inversedBy="ingredientsForMenu", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $menu;

    /**
     * @var integer
     *
     * @ORM\Column(name="quantity", type="integer", nullable=false)
     * @Assert\NotBlank(message="This value is mandatory: quantity")
     */
    private $quantity;

    /**
     * @var string
     *
     * @ORM\Column(name="unit", type="string", length=50, nullable=false)
     * @Assert\NotBlank(message="This value is mandatory: unit")
     * @Assert\Length(max=50)
     */
    private $unit;


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
     * @param integer $quantity
     * @return IngredientForMenu
     */
    public function setQuantity($quantity) {
        $this->quantity = $quantity;
        return $this;
    }

    /**
     * Get quantity
     * @return integer 
     */
    public function getQuantity() {
        return $this->quantity;
    }

    /**
     * Set unit
     * @param string $unit
     * @return IngredientForShoplist
     */
    public function setUnit($unit) {
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
     * @return string 
     */
    public function getUnit() {
        return $this->unit;
    }

    /**
     * Set ingredient
     *
     * @param \Nutri\IngredientBundle\Entity\Ingredient $ingredient
     * @return IngredientForMenu
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
     * Set menu
     *
     * @param \Nutri\RecipeBundle\Entity\Menu $menu
     * @return IngredientForMenu
     */
    public function setMenu(\Nutri\RecipeBundle\Entity\Menu $menu)
    {
        $this->menu = $menu;

        return $this;
    }

    /**
     * Get menu
     *
     * @return \Nutri\RecipeBundle\Entity\Menu 
     */
    public function getMenu()
    {
        return $this->menu;
    }
}
