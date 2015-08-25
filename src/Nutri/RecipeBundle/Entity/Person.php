<?php

namespace Nutri\RecipeBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Person
 *
 * @ORM\Table(name="nutri__recipe__person")
 * @ORM\Entity(repositoryClass="Nutri\RecipeBundle\Entity\PersonRepository")
 */
class Person
{
    const GENDER_MALE   = 'M';
    const GENDER_FEMALE = 'F';
    
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
     * @ORM\Column(name="name", type="string", length=50, nullable=false)
     * @Assert\Length(max=50)
     */
    private $name;

    /**
     * App User linked to this Person
     * @var Oliorga\AppBundle\Entity\User
     * 
     * @ORM\OneToOne(targetEntity="Oliorga\AppBundle\Entity\User", inversedBy="person")
     * @ORM\JoinColumn(nullable=true)
     **/
    private $user;
    
    /**
     * @var integer
     *
     * @ORM\Column(name="weight", type="integer", nullable=true)
     */
    private $weight;

    /**
     * @var integer
     *
     * @ORM\Column(name="height", type="integer", nullable=true)
     */
    private $height;

    /**
     * @var integer
     *
     * @ORM\Column(name="age", type="integer", nullable=true)
     */
    private $age;

    /**
     * @var string
     *
     * @ORM\Column(name="gender", type="string", length=10, nullable=true)
     * @Assert\Length(max=10)
     */
    private $gender;
    
    /**
     * Menus for this Person
     * @var Nutri\RecipeBundle\Entity\Menu[]
     * 
     * @ORM\OneToMany(targetEntity="Nutri\RecipeBundle\Entity\Menu", mappedBy="person", cascade={"persist"})
     */
    private $menus;


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
    
    public function getKcalNeeded() {
        if($this->getGender() === NULL || $this->getWeight() === NULL || $this->getHeight() === NULL || $this->getAge() === NULL) {
            return NULL;
        }
        $basalMetabolicRate = ($this->getGender() === 'M' ? 25.9 : 23) * (pow($this->getWeight(),0.48) * pow($this->getHeight(), 0.5) * pow($this->getAge(), -0.13));
        return $basalMetabolicRate * 1.37;
    }
    
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
     * Set weight
     * @param integer $weight
     * @return Person
     */
    public function setWeight($weight) {
        $this->weight = $weight;
        return $this;
    }

    /**
     * Get weight
     * @return integer 
     */
    public function getWeight() {
        return $this->weight;
    }

    /**
     * Set height
     * @param integer $height
     * @return Person
     */
    public function setHeight($height) {
        $this->height = $height;
        return $this;
    }

    /**
     * Get height
     * @return integer 
     */
    public function getHeight() {
        return $this->height;
    }

    /**
     * Set age
     * @param integer $age
     * @return Person
     */
    public function setAge($age) {
        $this->age = $age;
        return $this;
    }

    /**
     * Get age
     * @return integer 
     */
    public function getAge() {
        return $this->age;
    }

    /**
     * Set gender
     * @param string $gender
     * @return Person
     */
    public function setGender($gender) {
        if (!in_array($gender, array(
                self::GENDER_FEMALE, 
                self::GENDER_MALE))) {
            throw new \InvalidArgumentException("Invalid 'gender' value");
        }        
        $this->gender = $gender;
        return $this;
    }

    /**
     * Get gender
     * @return string 
     */
    public function getGender() {
        return $this->gender;
    }

    /**
     * Set user
     *
     * @param \Oliorga\AppBundle\Entity\User $user
     * @return Person
     */
    public function setUser(\Oliorga\AppBundle\Entity\User $user)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \Oliorga\AppBundle\Entity\User 
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return Person
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->menus = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add menus
     *
     * @param \Nutri\RecipeBundle\Entity\Menu $menus
     * @return Person
     */
    public function addMenu(\Nutri\RecipeBundle\Entity\Menu $menus)
    {
        $this->menus[] = $menus;

        return $this;
    }

    /**
     * Remove menus
     *
     * @param \Nutri\RecipeBundle\Entity\Menu $menus
     */
    public function removeMenu(\Nutri\RecipeBundle\Entity\Menu $menus)
    {
        $this->menus->removeElement($menus);
    }

    /**
     * Get menus
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getMenus()
    {
        return $this->menus;
    }
}
