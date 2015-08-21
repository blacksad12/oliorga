<?php

namespace Nutri\RecipeBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * IngredientForShoplist
 *
 * @ORM\Table(name="nutri__recipe__ingredientforshoplist")
 * @ORM\Entity(repositoryClass="Nutri\RecipeBundle\Entity\IngredientForShoplistRepository")
 */
class IngredientForShoplist
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
     * Shoplist
     * @var Nutri\RecipeBundle\Entity\Shoplist
     * 
     * @Assert\NotBlank(message="This value is mandatory")
     * @ORM\ManyToOne(targetEntity="Nutri\RecipeBundle\Entity\Shoplist", inversedBy="ingredientsForShoplist", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $shoplist;
    
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
     * @param float $quantity
     * @return IngredientForShoplist
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
     * Set unit
     * @param string $unit
     * @return IngredientForShoplist
     */
    public function setUnit($unit) {
        if (!in_array($unit, array(
                \Nutri\IngredientBundle\Entity\Ingredient::UNIT_UNIT, 
                \Nutri\IngredientBundle\Entity\Ingredient::UNIT_GRAM, 
                \Nutri\IngredientBundle\Entity\Ingredient::UNIT_LITER))) {
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
     * @return IngredientForShoplist
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
     * Set shoplist
     *
     * @param \Nutri\RecipeBundle\Entity\Shoplist $shoplist
     * @return IngredientForShoplist
     */
    public function setShoplist(\Nutri\RecipeBundle\Entity\Shoplist $shoplist)
    {
        $this->shoplist = $shoplist;

        return $this;
    }

    /**
     * Get shoplist
     *
     * @return \Nutri\RecipeBundle\Entity\Shoplist 
     */
    public function getShoplist()
    {
        return $this->shoplist;
    }
}
