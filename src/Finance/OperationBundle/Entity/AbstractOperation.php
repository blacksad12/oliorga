<?php

namespace Finance\OperationBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * AbstractOperation
 *
 * @ORM\Table(name="finance__operation__abstractoperation")
 * @ORM\Entity(repositoryClass="Finance\OperationBundle\Entity\AbstractOperationRepository")
 * @ORM\InheritanceType("JOINED")
 * @ORM\DiscriminatorColumn(name="discr", type="string")
 * @ORM\DiscriminatorMap({
 *      "operation"                 = "Operation",
 *      "transferbetweenaccount"    = "TransferBetweenAccount",
 *  
 * })
 */
abstract class AbstractOperation
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
     * @var float
     *
     * @ORM\Column(name="amount", type="float", nullable=false)
     * @Assert\NotBlank(message="This value is mandatory: amount")
     */
    private $amount;

    /**
     * @var boolean
     *
     * @ORM\Column(name="isMarked", type="boolean", nullable=true)
     */
    private $isMarked;

    /**
     * @var boolean
     *
     * @ORM\Column(name="isMonthlyRecurrent", type="boolean", nullable=true)
     */
    private $isMonthlyRecurrent;
    
    /**
     * Day of month when the AbstractOperation is done, if $isMonthlyRecurrent is true
     * @var integer
     *
     * @ORM\Column(name="recurrenceDay", type="integer", nullable=true)
     */
    private $recurrenceDay;

    /**************************************************************************/
    ////////////////////////////////////////////////////////////////////////////
    //                             Constructor
    ////////////////////////////////////////////////////////////////////////////
    public function __construct() {
        $this->isMonthlyRecurrent = false;
    }

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
    
    /** ************************************************************************
     * Return the class name (without namespace) of this Entity
     * @return string
     **************************************************************************/
    public function getClassName() {
        return substr(strrchr(get_class($this), "\\"), 1);
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
     * Set date
     * @param \DateTime $date
     * @return AbstractOperation
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
     * Set amount
     * @param float $amount
     * @return AbstractOperation
     */
    public function setAmount($amount) {
        $this->amount = $amount;
        return $this;
    }

    /**
     * Get amount
     * @return float 
     */
    public function getAmount() {
        return $this->amount;
    }

    /**
     * Set isMarked
     * @param boolean $isMarked
     * @return AbstractOperation
     */
    public function setIsMarked($isMarked) {
        $this->isMarked = $isMarked;
        return $this;
    }

    /**
     * Get isMarked
     * @return boolean 
     */
    public function getIsMarked() {
        return $this->isMarked;
    }

    /**
     * Set isMonthlyRecurrent
     *
     * @param boolean $isMonthlyRecurrent
     * @return AbstractOperation
     */
    public function setIsMonthlyRecurrent($isMonthlyRecurrent)
    {
        $this->isMonthlyRecurrent = $isMonthlyRecurrent;

        return $this;
    }

    /**
     * Get isMonthlyRecurrent
     *
     * @return boolean 
     */
    public function getIsMonthlyRecurrent()
    {
        return $this->isMonthlyRecurrent;
    }

    /**
     * Set recurrenceDay
     *
     * @param integer $recurrenceDay
     * @return AbstractOperation
     */
    public function setRecurrenceDay($recurrenceDay)
    {
        $this->recurrenceDay = $recurrenceDay;

        return $this;
    }

    /**
     * Get recurrenceDay
     *
     * @return integer 
     */
    public function getRecurrenceDay()
    {
        return $this->recurrenceDay;
    }
}
