<?php

namespace Finance\InstitutionBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Institution
 *
 * @ORM\Table(name="finance__institution__institution")
 * @ORM\Entity(repositoryClass="Finance\InstitutionBundle\Entity\InstitutionRepository")
 * @ORM\InheritanceType("JOINED")
 * @ORM\DiscriminatorColumn(name="discr", type="string")
 * @ORM\DiscriminatorMap({
 *      "bank"      = "Bank",
 *      "broker"    = "Broker",
 *      "insurer"   = "Insurer",
 * })
 */
abstract class Institution
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
     * @ORM\Column(name="name", type="string", length=255, nullable=false)
     * @Assert\NotBlank(message="This value is mandatory: name")
     * @Assert\Length(max=255)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="address", type="string", length=255, nullable=true)
     * @Assert\Length(max=255)
     */
    private $address;
    
    /**
     * @var Finance\AccountBundle\Entity\Account[]
     * 
     * @ORM\OneToMany(targetEntity="Finance\AccountBundle\Entity\Account", mappedBy="institution")
     */
    private $accounts;

    /**************************************************************************/
    ////////////////////////////////////////////////////////////////////////////
    //                             Constructor
    ////////////////////////////////////////////////////////////////////////////
    public function __construct() {
        $this->accounts = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set name
     * @param string $name
     * @return Institution
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
     * Set address
     * @param string $address
     * @return Institution
     */
    public function setAddress($address) {
        $this->address = $address;
        return $this;
    }

    /**
     * Get address
     * @return string 
     */
    public function getAddress() {
        return $this->address;
    }
    
    /**
     * Add accounts
     *
     * @param \Finance\AccountBundle\Entity\Account $accounts
     * @return Institution
     */
    public function addAccount(\Finance\AccountBundle\Entity\Account $accounts)
    {
        $this->accounts[] = $accounts;

        return $this;
    }

    /**
     * Remove accounts
     *
     * @param \Finance\AccountBundle\Entity\Account $accounts
     */
    public function removeAccount(\Finance\AccountBundle\Entity\Account $accounts)
    {
        $this->accounts->removeElement($accounts);
    }

    /**
     * Get accounts
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getAccounts()
    {
        return $this->accounts;
    }
}
