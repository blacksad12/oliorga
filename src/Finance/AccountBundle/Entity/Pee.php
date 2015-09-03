<?php

namespace Finance\AccountBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Pee
 *
 * @ORM\Table(name="finance__account__pee")
 * @ORM\Entity(repositoryClass="Finance\AccountBundle\Entity\PeeRepository")
 */
class Pee extends Category
{
    /**
     * @var string
     *
     * @ORM\Column(name="company", type="string", length=255, nullable=true)
     * @Assert\Length(max=255)
     */
    private $company;


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
     * Set company
     * @param string $company
     * @return Pee
     */
    public function setCompany($company) {
        $this->company = $company;
        return $this;
    }

    /**
     * Get company
     * @return string 
     */
    public function getCompany() {
        return $this->company;
    }
}
