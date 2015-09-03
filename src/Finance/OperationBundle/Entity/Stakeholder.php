<?php

namespace Finance\OperationBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Stakeholder
 *
 * @ORM\Table(name="finance__operation__stakeholder")
 * @ORM\Entity(repositoryClass="Finance\OperationBundle\Entity\StakeholderRepository")
 */
class Stakeholder
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
     * @var Finance\OperationBundle\Entity\Stakeholder
     * 
     * Note : Proprietary side
     * @ORM\ManyToOne(targetEntity="Finance\OperationBundle\Entity\Stakeholder", inversedBy="childrenStakeholders", cascade={"persist"})
     * @ORM\JoinColumn(nullable=true)
     */
    private $parent;
    
    /**
     * @var Finance\OperationBundle\Entity\Stakeholder[]
     * 
     * @ORM\OneToMany(targetEntity="Finance\OperationBundle\Entity\Stakeholder", mappedBy="parent", cascade={"persist"})
     */
    private $childrenStakeholders;

    /**
     * @var Finance\OperationBundle\Entity\Operation[]
     * 
     * @ORM\OneToMany(targetEntity="Finance\OperationBundle\Entity\Operation", mappedBy="stakeholder")
     */
    private $operations;

    /**************************************************************************/
    ////////////////////////////////////////////////////////////////////////////
    //                             Constructor
    ////////////////////////////////////////////////////////////////////////////
    public function __construct() {
        $this->childrenStakeholders = new \Doctrine\Common\Collections\ArrayCollection();
        $this->operations           = new \Doctrine\Common\Collections\ArrayCollection();
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
     * @return Stakeholder
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
     * Set parent
     *
     * @param \Finance\OperationBundle\Entity\Stakeholder $parent
     * @return Stakeholder
     */
    public function setParent(\Finance\OperationBundle\Entity\Stakeholder $parent = null)
    {
        $this->parent = $parent;

        return $this;
    }

    /**
     * Get parent
     *
     * @return \Finance\OperationBundle\Entity\Stakeholder 
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * Add childrenStakeholders
     *
     * @param \Finance\OperationBundle\Entity\Stakeholder $childrenStakeholders
     * @return Stakeholder
     */
    public function addChildrenStakeholder(\Finance\OperationBundle\Entity\Stakeholder $childrenStakeholders)
    {
        $this->childrenStakeholders[] = $childrenStakeholders;

        return $this;
    }

    /**
     * Remove childrenStakeholders
     *
     * @param \Finance\OperationBundle\Entity\Stakeholder $childrenStakeholders
     */
    public function removeChildrenStakeholder(\Finance\OperationBundle\Entity\Stakeholder $childrenStakeholders)
    {
        $this->childrenStakeholders->removeElement($childrenStakeholders);
    }

    /**
     * Get childrenStakeholders
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getChildrenStakeholders()
    {
        return $this->childrenStakeholders;
    }

    /**
     * Add operations
     *
     * @param \Finance\OperationBundle\Entity\Operation $operations
     * @return Stakeholder
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
}
