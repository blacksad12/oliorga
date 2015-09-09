<?php

namespace Finance\OperationBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Category
 *
 * @ORM\Table(name="finance__operation__category")
 * @ORM\Entity(repositoryClass="Finance\OperationBundle\Entity\CategoryRepository")
 */
class Category
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
     * @ORM\Column(name="name", type="string", length=100, nullable=false)
     * @Assert\NotBlank(message="This value is mandatory: name")
     * @Assert\Length(max=100)
     */
    private $name;
    
    /**
     * @var Finance\OperationBundle\Entity\Category
     * 
     * Note : Proprietary side
     * @ORM\ManyToOne(targetEntity="Finance\OperationBundle\Entity\Category", inversedBy="childrenCategories", cascade={"persist"})
     * @ORM\JoinColumn(nullable=true)
     */
    private $parent;
    
    /**
     * @var Finance\OperationBundle\Entity\Category[]
     * 
     * @ORM\OneToMany(targetEntity="Finance\OperationBundle\Entity\Category", mappedBy="parent", cascade={"persist"})
     */
    private $childrenCategories;
    
    /**
     * @var Finance\OperationBundle\Entity\Operation[]
     * 
     * @ORM\OneToMany(targetEntity="Finance\OperationBundle\Entity\Operation", mappedBy="category")
     */
    private $operations;

    /**
     * @var boolean
     *
     * @ORM\Column(name="isObselete", type="boolean", nullable=true)
     */
    private $isObselete;
    
    /**
     * @var string
     *
     * @ORM\Column(name="hexColor", type="string", length=100, nullable=true)
     * @Assert\Length(max=100)
     */
    private $hexColor;
    
    /**************************************************************************/
    ////////////////////////////////////////////////////////////////////////////
    //                             Constructor
    ////////////////////////////////////////////////////////////////////////////
    public function __construct() {
        $this->childrenCategories   = new \Doctrine\Common\Collections\ArrayCollection();
        $this->operations           = new \Doctrine\Common\Collections\ArrayCollection();
        $this->isObselete           = true;
    }

    /**************************************************************************/
    ////////////////////////////////////////////////////////////////////////////
    //                             To String
    ////////////////////////////////////////////////////////////////////////////
    public function __toString() {
        $string = '';
        if($this->getParent() !== NULL) {
            $string .= $this->getParent(). ' / ';
        }
        $string .= $this->getName();
        return $string;
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
     * @return Category
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
        if(!$this->isObselete){
            return '--'.$this->name;
        }
        return $this->name;
    }
    
    /**
     * Set parent
     *
     * @param \Finance\OperationBundle\Entity\Category $parent
     * @return Category
     */
    public function setParent(\Finance\OperationBundle\Entity\Category $parent = null)
    {
        $this->parent = $parent;

        return $this;
    }

    /**
     * Get parent
     *
     * @return \Finance\OperationBundle\Entity\Category 
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * Add childrenCategories
     *
     * @param \Finance\OperationBundle\Entity\Category $childrenCategories
     * @return Category
     */
    public function addChildrenCategory(\Finance\OperationBundle\Entity\Category $childrenCategories)
    {
        $this->childrenCategories[] = $childrenCategories;

        return $this;
    }

    /**
     * Remove childrenCategories
     *
     * @param \Finance\OperationBundle\Entity\Category $childrenCategories
     */
    public function removeChildrenCategory(\Finance\OperationBundle\Entity\Category $childrenCategories)
    {
        $this->childrenCategories->removeElement($childrenCategories);
    }

    /**
     * Get childrenCategories
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getChildrenCategories()
    {
        return $this->childrenCategories;
    }

    /**
     * Add operations
     *
     * @param \Finance\OperationBundle\Entity\Operation $operations
     * @return Category
     */
    public function addOperation(\Finance\OperationBundle\Entity\Operation $operations)
    {
        $this->operations[] = $operations;

        return $this;
    }

    /**
     * Remove operations
     *
     * @param \Finance\OperationBundle\Entity\Operation $operations
     */
    public function removeOperation(\Finance\OperationBundle\Entity\Operation $operations)
    {
        $this->operations->removeElement($operations);
    }

    /**
     * Get operations
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getOperations()
    {
        return $this->operations;
    }

    /**
     * Set isObselete
     *
     * @param boolean $isObselete
     * @return Category
     */
    public function setIsObselete($isObselete)
    {
        $this->isObselete = $isObselete;

        return $this;
    }

    /**
     * Get isObselete
     *
     * @return boolean 
     */
    public function getIsObselete()
    {
        return $this->isObselete;
    }

    /**
     * Set hexColor
     *
     * @param string $hexColor
     * @return Category
     */
    public function setHexColor($hexColor)
    {
        $this->hexColor = $hexColor;

        return $this;
    }

    /**
     * Get hexColor
     *
     * @return string 
     */
    public function getHexColor()
    {
        return $this->hexColor;
    }
}
