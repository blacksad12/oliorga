<?php

namespace Finance\AccountBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Performance
 *
 * @ORM\Table(name="finance__account__performance")
 * @ORM\Entity(repositoryClass="Finance\AccountBundle\Entity\PerformanceRepository")
 */
class Performance
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
     * @var Finance\AccountBundle\Entity\Livret
     * 
     * Note : Proprietary side
     * @ORM\ManyToOne(targetEntity="Finance\AccountBundle\Entity\Livret", inversedBy="performances", cascade={"persist"})
     * @ORM\JoinColumn(nullable=true)
     */
    private $livret;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dateChange", type="date", nullable=false)
     * @Assert\NotBlank(message="This value is mandatory: dateChange")
     */
    private $dateChange;

    /**
     * @var float
     *
     * @ORM\Column(name="rate", type="float", nullable=false)
     * @Assert\NotBlank(message="This value is mandatory: rate")
     */
    private $rate;


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
     * Set dateChange
     * @param \DateTime $dateChange
     * @return Performance
     */
    public function setDateChange($dateChange) {
        $this->dateChange = $dateChange;
        return $this;
    }

    /**
     * Get dateChange
     * @return \DateTime 
     */
    public function getDateChange() {
        return $this->dateChange;
    }

    /**
     * Set rate
     * @param float $rate
     * @return Performance
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

    /**
     * Set livret
     *
     * @param \Finance\AccountBundle\Entity\Livret $livret
     * @return Performance
     */
    public function setLivret(\Finance\AccountBundle\Entity\Livret $livret = null)
    {
        $this->livret = $livret;

        return $this;
    }

    /**
     * Get livret
     *
     * @return \Finance\AccountBundle\Entity\Livret 
     */
    public function getLivret()
    {
        return $this->livret;
    }
}
