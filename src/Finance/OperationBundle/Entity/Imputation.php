<?php

namespace Finance\OperationBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Imputation
 *
 * @ORM\Table(name="finance__operation__imputation")
 * @ORM\Entity(repositoryClass="Finance\OperationBundle\Entity\ImputationRepository")
 */
class Imputation
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
     * @var Finance\OperationBundle\Entity\Imputation
     * 
     * Note : Proprietary side
     * @ORM\ManyToOne(targetEntity="Finance\OperationBundle\Entity\Imputation", inversedBy="childrenImputations", cascade={"persist"})
     * @ORM\JoinColumn(nullable=true)
     */
    private $parent;
    
    /**
     * @var Finance\OperationBundle\Entity\Imputation[]
     * 
     * @ORM\OneToMany(targetEntity="Finance\OperationBundle\Entity\Imputation", mappedBy="parent", cascade={"persist"})
     */
    private $childrenImputations;

    /**
     * @var Finance\OperationBundle\Entity\Operation[]
     * 
     * @ORM\OneToMany(targetEntity="Finance\OperationBundle\Entity\Operation", mappedBy="imputation")
     */
    private $operations;
    
    /**
     * @var \DateTime
     *
     * @ORM\Column(name="startDate", type="date", nullable=true)
     */
    private $startDate;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="endDate", type="date", nullable=true)
     */
    private $endDate;

    /**************************************************************************/
    ////////////////////////////////////////////////////////////////////////////
    //                             Constructor
    ////////////////////////////////////////////////////////////////////////////
    public function __construct() {
        $this->childrenImputations  = new \Doctrine\Common\Collections\ArrayCollection();
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
     * @return Imputation
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
     * @param \Finance\OperationBundle\Entity\Imputation $parent
     * @return Imputation
     */
    public function setParent(\Finance\OperationBundle\Entity\Imputation $parent = null)
    {
        $this->parent = $parent;

        return $this;
    }

    /**
     * Get parent
     *
     * @return \Finance\OperationBundle\Entity\Imputation 
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * Add childrenImputations
     *
     * @param \Finance\OperationBundle\Entity\Imputation $childrenImputations
     * @return Imputation
     */
    public function addChildrenImputation(\Finance\OperationBundle\Entity\Imputation $childrenImputations)
    {
        $this->childrenImputations[] = $childrenImputations;

        return $this;
    }

    /**
     * Remove childrenImputations
     *
     * @param \Finance\OperationBundle\Entity\Imputation $childrenImputations
     */
    public function removeChildrenImputation(\Finance\OperationBundle\Entity\Imputation $childrenImputations)
    {
        $this->childrenImputations->removeElement($childrenImputations);
    }

    /**
     * Get childrenImputations
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getChildrenImputations()
    {
        return $this->childrenImputations;
    }

    /**
     * Add operations
     *
     * @param \Finance\OperationBundle\Entity\Operation $operations
     * @return Imputation
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
     * Set startDate
     *
     * @param \DateTime $startDate
     * @return Imputation
     */
    public function setStartDate($startDate)
    {
        $this->startDate = $startDate;

        return $this;
    }

    /**
     * Get startDate
     *
     * @return \DateTime 
     */
    public function getStartDate()
    {
        return $this->startDate;
    }

    /**
     * Set endDate
     *
     * @param \DateTime $endDate
     * @return Imputation
     */
    public function setEndDate($endDate)
    {
        $this->endDate = $endDate;

        return $this;
    }

    /**
     * Get endDate
     *
     * @return \DateTime 
     */
    public function getEndDate()
    {
        return $this->endDate;
    }
}
