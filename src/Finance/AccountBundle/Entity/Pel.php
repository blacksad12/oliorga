<?php

namespace Finance\AccountBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Pel
 *
 * @ORM\Table(name="finance__account__pel")
 * @ORM\Entity(repositoryClass="Finance\AccountBundle\Entity\PelRepository")
 */
class Pel extends Category
{
    /**
     * @var integer
     *
     * @ORM\Column(name="maximumAmount", type="integer", nullable=true)
     */
    private $maximumAmount;

    /**
     * @var float
     *
     * @ORM\Column(name="rate", type="float", nullable=true)
     */
    private $rate;


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
     * @return Pel
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
     * Set rate
     * @param float $rate
     * @return Pel
     */
    public function setRate($rate) {
        $this->rate = $rate;
        return $this;
    }

    /**
     * Get rate
     * @return float 
     */
    public function getRate() {
        return $this->rate;
    }
}
