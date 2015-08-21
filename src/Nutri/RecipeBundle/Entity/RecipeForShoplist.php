<?php

namespace Nutri\RecipeBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * RecipeForShoplist
 *
 * @ORM\Table(name="nutri__recipe__recipeforshoplist")
 * @ORM\Entity(repositoryClass="Nutri\RecipeBundle\Entity\RecipeForShoplistRepository")
 */
class RecipeForShoplist
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
     * Recipe
     * @var Nutri\RecipeBundle\Entity\Recipe
     * 
     * @Assert\NotBlank(message="This value is mandatory")
     * @ORM\ManyToOne(targetEntity="Nutri\RecipeBundle\Entity\Recipe")
     * @ORM\JoinColumn(nullable=false)
     */
    private $recipe;
    
    /**
     * Shoplist
     * @var Nutri\RecipeBundle\Entity\Shoplist
     * 
     * @Assert\NotBlank(message="This value is mandatory")
     * @ORM\ManyToOne(targetEntity="Nutri\RecipeBundle\Entity\Shoplist", inversedBy="recipesForShoplist", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $shoplist;
    
    /**
     * @var integer
     *
     * @ORM\Column(name="nbPeople", type="integer", nullable=false)
     * @Assert\NotBlank(message="This value is mandatory: nbPeople")
     */
    private $nbPeople;


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
     * Set nbPeople
     * @param integer $nbPeople
     * @return RecipeForShoplist
     */
    public function setNbPeople($nbPeople) {
        $this->nbPeople = $nbPeople;
        return $this;
    }

    /**
     * Get nbPeople
     * @return integer 
     */
    public function getNbPeople() {
        return $this->nbPeople;
    }

    /**
     * Set recipe
     *
     * @param \Nutri\RecipeBundle\Entity\Recipe $recipe
     * @return RecipeForShoplist
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

    /**
     * Set shoplist
     *
     * @param \Nutri\RecipeBundle\Entity\Shoplist $shoplist
     * @return RecipeForShoplist
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
