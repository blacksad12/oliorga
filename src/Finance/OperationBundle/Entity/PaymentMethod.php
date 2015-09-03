<?php

namespace Finance\OperationBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * PaymentMethod
 *
 * @ORM\Table(name="finance__operation__paymentmethod")
 * @ORM\Entity(repositoryClass="Finance\OperationBundle\Entity\PaymentMethodRepository")
 */
class PaymentMethod
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
     * @ORM\Column(name="name", type="string", length=100, nullable=false)
     * @Assert\NotBlank(message="This value is mandatory: name")
     * @Assert\Length(max=100)
     */
    private $name;

    /**
     * @var Finance\OperationBundle\Entity\Operation[]
     * 
     * @ORM\OneToMany(targetEntity="Finance\OperationBundle\Entity\Operation", mappedBy="paymentMethod")
     */
    private $operations;

    /**************************************************************************/
    ////////////////////////////////////////////////////////////////////////////
    //                             Constructor
    ////////////////////////////////////////////////////////////////////////////
    public function __construct() {
        $this->operations = new \Doctrine\Common\Collections\ArrayCollection();
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
     * @return PaymentMethod
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
     * Add operations
     *
     * @param \Finance\OperationBundle\Entity\Operation $operations
     * @return PaymentMethod
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
}
