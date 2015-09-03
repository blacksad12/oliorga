<?php

namespace Finance\AccountBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * AssuranceVie
 *
 * @ORM\Table(name="finance__account__assurancevie")
 * @ORM\Entity(repositoryClass="Finance\AccountBundle\Entity\AssuranceVieRepository")
 */
class AssuranceVie extends Category
{
    /**
     * @var Finance\InstitutionBundle\Entity\Insurer
     * 
     * Note : Proprietary side
     * @ORM\ManyToOne(targetEntity="Finance\InstitutionBundle\Entity\Insurer", inversedBy="assuranceVies", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $insurer;

    /**
     * @var float
     *
     * @ORM\Column(name="fraisDeGestionUc", type="float", nullable=true)
     */
    private $fraisDeGestionUc;

    /**
     * @var float
     *
     * @ORM\Column(name="fraisDeVersement", type="float", nullable=true)
     */
    private $fraisDeVersement;

    /**
     * @var integer
     *
     * @ORM\Column(name="versementInitialMin", type="integer", nullable=true)
     */
    private $versementInitialMin;

    /**
     * @var integer
     *
     * @ORM\Column(name="versementPonctuelMin", type="integer", nullable=true)
     */
    private $versementPonctuelMin;

    /**
     * @var integer
     *
     * @ORM\Column(name="versementProgrammeMin", type="integer", nullable=true)
     */
    private $versementProgrammeMin;


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
     * Set fraisDeGestionUc
     * @param float $fraisDeGestionUc
     * @return AssuranceVie
     */
    public function setFraisDeGestionUc($fraisDeGestionUc) {
        $this->fraisDeGestionUc = $fraisDeGestionUc;
        return $this;
    }

    /**
     * Get fraisDeGestionUc
     * @return float 
     */
    public function getFraisDeGestionUc() {
        return $this->fraisDeGestionUc;
    }

    /**
     * Set fraisDeVersement
     * @param float $fraisDeVersement
     * @return AssuranceVie
     */
    public function setFraisDeVersement($fraisDeVersement) {
        $this->fraisDeVersement = $fraisDeVersement;
        return $this;
    }

    /**
     * Get fraisDeVersement
     * @return float 
     */
    public function getFraisDeVersement() {
        return $this->fraisDeVersement;
    }

    /**
     * Set versementInitialMin
     * @param integer $versementInitialMin
     * @return AssuranceVie
     */
    public function setVersementInitialMin($versementInitialMin) {
        $this->versementInitialMin = $versementInitialMin;
        return $this;
    }

    /**
     * Get versementInitialMin
     * @return integer 
     */
    public function getVersementInitialMin() {
        return $this->versementInitialMin;
    }

    /**
     * Set versementPonctuelMin
     * @param integer $versementPonctuelMin
     * @return AssuranceVie
     */
    public function setVersementPonctuelMin($versementPonctuelMin) {
        $this->versementPonctuelMin = $versementPonctuelMin;
        return $this;
    }

    /**
     * Get versementPonctuelMin
     * @return integer 
     */
    public function getVersementPonctuelMin() {
        return $this->versementPonctuelMin;
    }

    /**
     * Set versementProgrammeMin
     * @param integer $versementProgrammeMin
     * @return AssuranceVie
     */
    public function setVersementProgrammeMin($versementProgrammeMin) {
        $this->versementProgrammeMin = $versementProgrammeMin;
        return $this;
    }

    /**
     * Get versementProgrammeMin
     * @return integer 
     */
    public function getVersementProgrammeMin() {
        return $this->versementProgrammeMin;
    }

    /**
     * Set insurer
     *
     * @param \Finance\InstitutionBundle\Entity\Insurer $insurer
     * @return AssuranceVie
     */
    public function setInsurer(\Finance\InstitutionBundle\Entity\Insurer $insurer)
    {
        $this->insurer = $insurer;

        return $this;
    }

    /**
     * Get insurer
     *
     * @return \Finance\InstitutionBundle\Entity\Insurer 
     */
    public function getInsurer()
    {
        return $this->insurer;
    }
}
