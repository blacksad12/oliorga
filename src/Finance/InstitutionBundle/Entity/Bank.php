<?php

namespace Finance\InstitutionBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Bank
 *
 * @ORM\Table(name="finance__institution__bank")
 * @ORM\Entity(repositoryClass="Finance\InstitutionBundle\Entity\BankRepository")
 */
class Bank extends Institution
{
    /**
     * @var string
     *
     * @ORM\Column(name="bic", type="string", length=255, nullable=true)
     * @Assert\Length(max=255)
     */
    private $bic;

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
     * Set bic
     * @param string $bic
     * @return Bank
     */
    public function setBic($bic) {
        $this->bic = $bic;
        return $this;
    }

    /**
     * Get bic
     * @return string 
     */
    public function getBic() {
        return $this->bic;
    }
}
