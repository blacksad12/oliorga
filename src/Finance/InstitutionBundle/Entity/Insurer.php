<?php

namespace Finance\InstitutionBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Insurer
 *
 * @ORM\Table(name="finance__institution__insurer")
 * @ORM\Entity(repositoryClass="Finance\InstitutionBundle\Entity\InsurerRepository")
 */
class Insurer extends Institution
{
    /**
     * @var Finance\AccountBundle\Entity\AssuranceVie[]
     * 
     * @ORM\OneToMany(targetEntity="Finance\AccountBundle\Entity\AssuranceVie", mappedBy="insurer", cascade={"persist"})
     */
    private $assuranceVies;
    
    /**************************************************************************/
    ////////////////////////////////////////////////////////////////////////////
    //                             Constructor
    ////////////////////////////////////////////////////////////////////////////
    public function __construct() {
        parent::__construct();
        $this->assuranceVies = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Add assuranceVies
     *
     * @param \Finance\AccountBundle\Entity\AssuranceVie $assuranceVies
     * @return Insurer
     */
    public function addAssuranceVy(\Finance\AccountBundle\Entity\AssuranceVie $assuranceVies)
    {
        $this->assuranceVies[] = $assuranceVies;

        return $this;
    }

    /**
     * Remove assuranceVies
     *
     * @param \Finance\AccountBundle\Entity\AssuranceVie $assuranceVies
     */
    public function removeAssuranceVy(\Finance\AccountBundle\Entity\AssuranceVie $assuranceVies)
    {
        $this->assuranceVies->removeElement($assuranceVies);
    }

    /**
     * Get assuranceVies
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getAssuranceVies()
    {
        return $this->assuranceVies;
    }
}
