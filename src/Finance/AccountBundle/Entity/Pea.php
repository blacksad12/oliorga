<?php

namespace Finance\AccountBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Pea
 *
 * @ORM\Table(name="finance__account__pea")
 * @ORM\Entity(repositoryClass="Finance\AccountBundle\Entity\PeaRepository")
 */
class Pea extends Category
{
    /**
     * @var integer
     *
     * @ORM\Column(name="maximumAmount", type="integer", nullable=true)
     */
    private $maximumAmount;


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
     * @return Pea
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
}
