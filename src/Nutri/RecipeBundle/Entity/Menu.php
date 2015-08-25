<?php

namespace Nutri\RecipeBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Menu
 *
 * @ORM\Table(name="nutri__recipe__menu")
 * @ORM\Entity(repositoryClass="Nutri\RecipeBundle\Entity\MenuRepository")
 */
class Menu
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
     * @ORM\Column(name="date", type="date", nullable=true)
     */
    private $date;

    /**
     * Ingredient
     * @var Nutri\RecipeBundle\Entity\Person
     * 
     * @Assert\NotBlank(message="This value is mandatory")
     * @ORM\ManyToOne(targetEntity="Nutri\RecipeBundle\Entity\Person", inversedBy="menus", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $person;

    /**
     * Ingredients in this Menu
     * @var Nutri\RecipeBundle\Entity\IngredientForMenu[]
     * 
     * @ORM\OneToMany(targetEntity="Nutri\RecipeBundle\Entity\IngredientForMenu", mappedBy="menu", cascade={"persist"})
     */
    private $ingredientsForMenu;
    
    /**
     * Recipes in this Menu
     * @var Nutri\RecipeBundle\Entity\Recipe[]
     * 
     * Note: owning side
     * @ORM\ManyToMany(targetEntity="Nutri\RecipeBundle\Entity\Recipe", inversedBy="menus", cascade={"persist"})
     * @ORM\JoinTable(name="nutri__recipe__recipeformenu")
     */
    private $recipes;

    /**************************************************************************/
    ////////////////////////////////////////////////////////////////////////////
    //                             Constructor
    ////////////////////////////////////////////////////////////////////////////
    public function __construct() {
        $this->ingredientsForMenu = new \Doctrine\Common\Collections\ArrayCollection();
        $this->recipes = new \Doctrine\Common\Collections\ArrayCollection();
    }

    
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
     * @return Menu
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
     * Add ingredientsForMenu
     *
     * @param \Nutri\RecipeBundle\Entity\IngredientForMenu $ingredientsForMenu
     * @return Menu
     */
    public function addIngredientsForMenu(\Nutri\RecipeBundle\Entity\IngredientForMenu $ingredientsForMenu)
    {
        $this->ingredientsForMenu[] = $ingredientsForMenu;

        return $this;
    }

    /**
     * Remove ingredientsForMenu
     *
     * @param \Nutri\RecipeBundle\Entity\IngredientForMenu $ingredientsForMenu
     */
    public function removeIngredientsForMenu(\Nutri\RecipeBundle\Entity\IngredientForMenu $ingredientsForMenu)
    {
        $this->ingredientsForMenu->removeElement($ingredientsForMenu);
    }

    /**
     * Get ingredientsForMenu
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getIngredientsForMenu()
    {
        return $this->ingredientsForMenu;
    }

    /**
     * Add recipes
     *
     * @param \Nutri\RecipeBundle\Entity\Recipe $recipe
     * @return Menu
     */
    public function addRecipe(\Nutri\RecipeBundle\Entity\Recipe $recipe)
    {
        $this->recipes[] = $recipe;

        return $this;
    }

    /**
     * Remove recipes
     *
     * @param \Nutri\RecipeBundle\Entity\Recipe $recipe
     */
    public function removeRecipe(\Nutri\RecipeBundle\Entity\Recipe $recipe)
    {
        $this->recipes->removeElement($recipe);
    }

    /**
     * Get recipes
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getRecipes()
    {
        return $this->recipes;
    }

    /**
     * Set person
     *
     * @param \Nutri\RecipeBundle\Entity\Person $person
     * @return Menu
     */
    public function setPerson(\Nutri\RecipeBundle\Entity\Person $person)
    {
        $this->person = $person;

        return $this;
    }

    /**
     * Get person
     *
     * @return \Nutri\RecipeBundle\Entity\Person 
     */
    public function getPerson()
    {
        return $this->person;
    }
}
