<?php

namespace Finance\AccountBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Account
 *
 * @ORM\Table(name="finance__account__account")
 * @ORM\Entity(repositoryClass="Finance\AccountBundle\Entity\AccountRepository")
 */
class Account
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
     * @var Finance\AccountBundle\Entity\Category
     * 
     * Note : Proprietary side
     * @ORM\ManyToOne(targetEntity="Finance\AccountBundle\Entity\Category", inversedBy="accounts", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $category;

    /**
     * @var Finance\InstitutionBundle\Entity\Institution
     * 
     * Note : Proprietary side
     * @ORM\ManyToOne(targetEntity="Finance\InstitutionBundle\Entity\Institution", inversedBy="accounts", cascade={"persist"})
     * @ORM\JoinColumn(nullable=true)
     */
    private $institution;

    /**
     * @var string
     *
     * @ORM\Column(name="number", type="string", length=100, nullable=true)
     * @Assert\Length(max=100)
     */
    private $number;

    /**
     * @var string
     *
     * @ORM\Column(name="iban", type="string", length=100, nullable=true)
     * @Assert\Length(max=100)
     */
    private $iban;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="openDate", type="date", nullable=true)
     */
    private $openDate;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="closeDate", type="date", nullable=true)
     */
    private $closeDate;

    /**
     * @var Finance\OperationBundle\Entity\Operation[]
     * 
     * @ORM\OneToMany(targetEntity="Finance\OperationBundle\Entity\Operation", mappedBy="account", cascade={"persist"})
     */
    private $operations;

    /**
     * @var Finance\OperationBundle\Entity\TransferBetweenAccount[]
     * 
     * @ORM\OneToMany(targetEntity="Finance\OperationBundle\Entity\TransferBetweenAccount", mappedBy="sourceAccount", cascade={"persist"})
     */
    private $outcomeTransfers;

    /**
     * @var Finance\OperationBundle\Entity\TransferBetweenAccount[]
     * 
     * @ORM\OneToMany(targetEntity="Finance\OperationBundle\Entity\TransferBetweenAccount", mappedBy="destinationAccount", cascade={"persist"})
     */
    private $incomeTransfers;
    
    /**
     * @var Oliorga\AppBundle\Entity\User
     * 
     * Note : Proprietary side
     * @ORM\ManyToOne(targetEntity="Oliorga\AppBundle\Entity\User")
     * @ORM\JoinColumn(nullable=true)
     */
    private $owner;

    /**************************************************************************/
    ////////////////////////////////////////////////////////////////////////////
    //                             Constructor
    ////////////////////////////////////////////////////////////////////////////
    public function __construct() {
        $this->outcomeTransfers = new \Doctrine\Common\Collections\ArrayCollection();
        $this->incomeTransfers  = new \Doctrine\Common\Collections\ArrayCollection();
    }
    
    /**************************************************************************/
    ////////////////////////////////////////////////////////////////////////////
    //                             To String
    ////////////////////////////////////////////////////////////////////////////
    public function __toString() {
        return $this->getCategory().' '.$this->getInstitution().' ('.$this->getOwner()->getPerson()->getName().')';
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
     * Set number
     * @param string $number
     * @return Account
     */
    public function setNumber($number) {
        $this->number = $number;
        return $this;
    }

    /**
     * Get number
     * @return string 
     */
    public function getNumber() {
        return $this->number;
    }

    /**
     * Set iban
     * @param string $iban
     * @return Account
     */
    public function setIban($iban) {
        $this->iban = $iban;
        return $this;
    }

    /**
     * Get iban
     * @return string 
     */
    public function getIban() {
        return $this->iban;
    }

    /**
     * Set openDate
     * @param \DateTime $openDate
     * @return Account
     */
    public function setOpenDate($openDate) {
        $this->openDate = $openDate;
        return $this;
    }

    /**
     * Get openDate
     * @return \DateTime 
     */
    public function getOpenDate() {
        return $this->openDate;
    }

    /**
     * Set closeDate
     * @param \DateTime $closeDate
     * @return Account
     */
    public function setCloseDate($closeDate) {
        $this->closeDate = $closeDate;
        return $this;
    }

    /**
     * Get closeDate
     * @return \DateTime 
     */
    public function getCloseDate() {
        return $this->closeDate;
    }

    /**
     * Set category
     *
     * @param \Finance\AccountBundle\Entity\Category $category
     * @return Account
     */
    public function setCategory(\Finance\AccountBundle\Entity\Category $category)
    {
        $this->category = $category;

        return $this;
    }

    /**
     * Get category
     *
     * @return \Finance\AccountBundle\Entity\Category 
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * Set institution
     *
     * @param \Finance\InstitutionBundle\Entity\Institution $institution
     * @return Account
     */
    public function setInstitution(\Finance\InstitutionBundle\Entity\Institution $institution = NULL)
    {
        $this->institution = $institution;

        return $this;
    }

    /**
     * Get institution
     *
     * @return \Finance\InstitutionBundle\Entity\Institution 
     */
    public function getInstitution()
    {
        return $this->institution;
    }

    /**
     * Add outcomeTransfers
     *
     * @param \Finance\OperationBundle\Entity\TransferBetweenAccount $outcomeTransfers
     * @return Account
     */
    public function addOutcomeTransfer(\Finance\OperationBundle\Entity\TransferBetweenAccount $outcomeTransfers)
    {
        $this->outcomeTransfers[] = $outcomeTransfers;

        return $this;
    }

    /**
     * Remove outcomeTransfers
     *
     * @param \Finance\OperationBundle\Entity\TransferBetweenAccount $outcomeTransfers
     */
    public function removeOutcomeTransfer(\Finance\OperationBundle\Entity\TransferBetweenAccount $outcomeTransfers)
    {
        $this->outcomeTransfers->removeElement($outcomeTransfers);
    }

    /**
     * Get outcomeTransfers
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getOutcomeTransfers()
    {
        return $this->outcomeTransfers;
    }

    /**
     * Add incomeTransfers
     *
     * @param \Finance\OperationBundle\Entity\TransferBetweenAccount $incomeTransfers
     * @return Account
     */
    public function addIncomeTransfer(\Finance\OperationBundle\Entity\TransferBetweenAccount $incomeTransfers)
    {
        $this->incomeTransfers[] = $incomeTransfers;

        return $this;
    }

    /**
     * Remove incomeTransfers
     *
     * @param \Finance\OperationBundle\Entity\TransferBetweenAccount $incomeTransfers
     */
    public function removeIncomeTransfer(\Finance\OperationBundle\Entity\TransferBetweenAccount $incomeTransfers)
    {
        $this->incomeTransfers->removeElement($incomeTransfers);
    }

    /**
     * Get incomeTransfers
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getIncomeTransfers()
    {
        return $this->incomeTransfers;
    }

    /**
     * Add operations
     *
     * @param \Finance\OperationBundle\Entity\Operation $operations
     * @return Account
     */
    public function addOperation(\Finance\OperationBundle\Entity\Operation $operations)
    {
        $this->operations[] = $operations;

        return $this;
    }

    /**
     * Remove operations
     *
     * @param \Finance\OperationBundle\Entity\Operation $operations
     */
    public function removeOperation(\Finance\OperationBundle\Entity\Operation $operations)
    {
        $this->operations->removeElement($operations);
    }

    /**
     * Get operations
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getOperations()
    {
        return $this->operations;
    }

    /**
     * Set owner
     *
     * @param \Oliorga\AppBundle\Entity\User $owner
     * @return Account
     */
    public function setOwner(\Oliorga\AppBundle\Entity\User $owner = NULL)
    {
        $this->owner = $owner;

        return $this;
    }

    /**
     * Get owner
     *
     * @return \Oliorga\AppBundle\Entity\User 
     */
    public function getOwner()
    {
        return $this->owner;
    }
}
