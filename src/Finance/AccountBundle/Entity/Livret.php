<?php

namespace Finance\AccountBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Livret
 *
 * @ORM\Table(name="finance__account__livret")
 * @ORM\Entity(repositoryClass="Finance\AccountBundle\Entity\LivretRepository")
 */
class Livret extends Category
{
    /**
     * @var integer
     *
     * @ORM\Column(name="maximumAmount", type="integer", nullable=true)
     */
    private $maximumAmount;

    /**
     * @var Finance\AccountBundle\Entity\Performance[]
     * 
     * @ORM\OneToMany(targetEntity="Finance\AccountBundle\Entity\Performance", mappedBy="livret", cascade={"persist"})
     */
    private $performances;
    
    /**************************************************************************/
    ////////////////////////////////////////////////////////////////////////////
    //                             Constructor
    ////////////////////////////////////////////////////////////////////////////
    public function __construct() {
        parent::__construct();
        $this->performances = new \Doctrine\Common\Collections\ArrayCollection();
    }
    
    /**************************************************************************/
    ////////////////////////////////////////////////////////////////////////////
    //                             To String
    ////////////////////////////////////////////////////////////////////////////
    
    /**************************************************************************/
    ////////////////////////////////////////////////////////////////////////////
    //                  Custom setters and getters
    ////////////////////////////////////////////////////////////////////////////
    
    /**************************************************************************/
    ////////////////////////////////////////////////////////////////////////////
    //                  Attributes' setters and getters
    ////////////////////////////////////////////////////////////////////////////
    
    /**
     * Set maximumAmount
     * @param integer $maximumAmount
     * @return Livret
     */
    public function setMaximumAmount($maximumAmount) {
        $this->maximumAmount = $maximumAmount;
        return $this;
    }

    /**
     * Get maximumAmount
     * @return integer 
     */
    public function getMaximumAmount() {
        return $this->maximumAmount;
    }
    
    /**
     * Add performances
     *
     * @param \Finance\AccountBundle\Entity\Performance $performances
     * @return Livret
     */
    public function addPerformance(\Finance\AccountBundle\Entity\Performance $performances)
    {
        $this->performances[] = $performances;

        return $this;
    }

    /**
     * Remove performances
     *
     * @param \Finance\AccountBundle\Entity\Performance $performances
     */
    public function removePerformance(\Finance\AccountBundle\Entity\Performance $performances)
    {
        $this->performances->removeElement($performances);
    }

    /**
     * Get performances
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getPerformances()
    {
        return $this->performances;
    }
}
