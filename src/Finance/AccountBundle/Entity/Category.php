<?php

namespace Finance\AccountBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Category
 *
 * @ORM\Table(name="finance__account__category")
 * @ORM\Entity(repositoryClass="Finance\AccountBundle\Entity\CategoryRepository")
 * @ORM\InheritanceType("JOINED")
 * @ORM\DiscriminatorColumn(name="discr", type="string")
 * @ORM\DiscriminatorMap({
 *      "livret"            = "Livret",
 *      "assuranceVie"      = "AssuranceVie",  
 *      "ccp"               = "Ccp",  
 *      "cash"              = "Cash",  
 *      "pea"               = "Pea",  
 *      "pel"               = "Pel",  
 *      "pee"               = "Pee",  
 *      "pretAmortissable"  = "PretAmortissable",
 * })
 */
abstract class Category
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
     * @var Finance\AccountBundle\Entity\Account[]
     * 
     * @ORM\OneToMany(targetEntity="Finance\AccountBundle\Entity\Account", mappedBy="category", cascade={"persist"})
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
        if($this->getName() != NULL){
            $name = $this->getName().' ('.$this->getClassName().')';
        }
        else{
            $name = $this->getClassName();
        
        }
        return $name;
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
     * @return Category
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
     * Add accounts
     *
     * @param \Finance\AccountBundle\Entity\Account $accounts
     * @return Category
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
