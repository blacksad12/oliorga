<?php

namespace Nutri\RecipeBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Shoplist
 *
 * @ORM\Table(name="nutri__recipe__shoplist")
 * @ORM\Entity(repositoryClass="Nutri\RecipeBundle\Entity\ShoplistRepository")
 */
class Shoplist
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
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="date", nullable=false)
     * @Assert\NotBlank(message="This value is mandatory: date")
     */
    private $date;

    /**
     * Ingredients in this Shoplist
     * @var Nutri\RecipeBundle\Entity\IngredientForShoplist[]
     * 
     * @ORM\OneToMany(targetEntity="Nutri\RecipeBundle\Entity\IngredientForShoplist", mappedBy="shoplist", cascade={"persist"})
     */
    private $ingredientsForShoplist;
    
    /**
     * Recipes in this Shoplist
     * @var Nutri\RecipeBundle\Entity\RecipeForShoplist[]
     * 
     * @ORM\OneToMany(targetEntity="Nutri\RecipeBundle\Entity\RecipeForShoplist", mappedBy="shoplist", cascade={"persist"})
     */
    private $recipesForShoplist;

    /**************************************************************************/
    ////////////////////////////////////////////////////////////////////////////
    //                             To String
    ////////////////////////////////////////////////////////////////////////////
    public function __toString() {
        return '#'.$this->getId().' - '.$this->getDate()->format('d/m/Y');
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
     * Set date
     * @param \DateTime $date
     * @return Shoplist
     */
    public function setDate($date) {
        $this->date = $date;
        return $this;
    }

    /**
     * Get date
     * @return \DateTime 
     */
    public function getDate() {
        return $this->date;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->ingredientsForShoplist = new \Doctrine\Common\Collections\ArrayCollection();
        $this->recipesForShoplist = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add ingredientsForShoplist
     *
     * @param \Nutri\RecipeBundle\Entity\IngredientForShoplist $ingredientsForShoplist
     * @return Shoplist
     */
    public function addIngredientsForShoplist(\Nutri\RecipeBundle\Entity\IngredientForShoplist $ingredientsForShoplist)
    {
        $this->ingredientsForShoplist[] = $ingredientsForShoplist;

        return $this;
    }

    /**
     * Remove ingredientsForShoplist
     *
     * @param \Nutri\RecipeBundle\Entity\IngredientForShoplist $ingredientsForShoplist
     */
    public function removeIngredientsForShoplist(\Nutri\RecipeBundle\Entity\IngredientForShoplist $ingredientsForShoplist)
    {
        $this->ingredientsForShoplist->removeElement($ingredientsForShoplist);
    }

    /**
     * Get ingredientsForShoplist
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getIngredientsForShoplist()
    {
        return $this->ingredientsForShoplist;
    }

    /**
     * Add recipesForShoplist
     *
     * @param \Nutri\RecipeBundle\Entity\RecipeForShoplist $recipesForShoplist
     * @return Shoplist
     */
    public function addRecipesForShoplist(\Nutri\RecipeBundle\Entity\RecipeForShoplist $recipesForShoplist)
    {
        $this->recipesForShoplist[] = $recipesForShoplist;

        return $this;
    }

    /**
     * Remove recipesForShoplist
     *
     * @param \Nutri\RecipeBundle\Entity\RecipeForShoplist $recipesForShoplist
     */
    public function removeRecipesForShoplist(\Nutri\RecipeBundle\Entity\RecipeForShoplist $recipesForShoplist)
    {
        $this->recipesForShoplist->removeElement($recipesForShoplist);
    }

    /**
     * Get recipesForShoplist
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getRecipesForShoplist()
    {
        return $this->recipesForShoplist;
    }
}
