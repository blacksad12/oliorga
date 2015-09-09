<?php

namespace Finance\OperationBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Operation
 *
 * @ORM\Table(name="finance__operation__operation")
 * @ORM\Entity(repositoryClass="Finance\OperationBundle\Entity\OperationRepository")
 */
class Operation extends AbstractOperation
{
    /**
     * @var Finance\AccountBundle\Entity\Account
     * 
     * Note : Proprietary side
     * @ORM\ManyToOne(targetEntity="Finance\AccountBundle\Entity\Account", inversedBy="operations", cascade={"persist"})
     * @ORM\JoinColumn(nullable=true)
     */
    private $account;
    
    /**
     * @var Finance\OperationBundle\Entity\Category
     * 
     * Note : Proprietary side
     * @ORM\ManyToOne(targetEntity="Finance\OperationBundle\Entity\Category", inversedBy="operations", cascade={"persist"})
     * @ORM\JoinColumn(nullable=true)
     */
    private $category;
    
    /**
     * @var Finance\OperationBundle\Entity\Imputation
     * 
     * Note : Proprietary side
     * @ORM\ManyToOne(targetEntity="Finance\OperationBundle\Entity\Imputation", inversedBy="operations", cascade={"persist"})
     * @ORM\JoinColumn(nullable=true)
     */
    private $imputation;
    
    /**
     * @var Finance\OperationBundle\Entity\Stakeholder
     * 
     * Note : Proprietary side
     * @ORM\ManyToOne(targetEntity="Finance\OperationBundle\Entity\Stakeholder", inversedBy="operations", cascade={"persist"})
     * @ORM\JoinColumn(nullable=true)
     */
    private $stakeholder;
    
    /**
     * @var Finance\OperationBundle\Entity\PaymentMethod
     * 
     * Note : Proprietary side
     * @ORM\ManyToOne(targetEntity="Finance\OperationBundle\Entity\PaymentMethod", inversedBy="operations", cascade={"persist"})
     * @ORM\JoinColumn(nullable=true)
     */
    private $paymentMethod;
    
    /**
     * @var string
     *
     * @ORM\Column(name="comment", type="string", length=255, nullable=true)
     * @Assert\Length(max=255)
     */
    private $comment;

    /**************************************************************************/
    ////////////////////////////////////////////////////////////////////////////
    //                             Constructor
    ////////////////////////////////////////////////////////////////////////////
    public function __construct(\Finance\AccountBundle\Entity\Account $account) {
        $this->account = $account;
    }

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
     * Set comment
     * @param string $comment
     * @return Operation
     */
    public function setComment($comment) {
        $this->comment = $comment;
        return $this;
    }

    /**
     * Get comment
     * @return string 
     */
    public function getComment() {
        return $this->comment;
    }

    /**
     * Set category
     *
     * @param \Finance\OperationBundle\Entity\Category $category
     * @return Operation
     */
    public function setCategory(\Finance\OperationBundle\Entity\Category $category = null)
    {
        $this->category = $category;

        return $this;
    }

    /**
     * Get category
     *
     * @return \Finance\OperationBundle\Entity\Category 
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * Set imputation
     *
     * @param \Finance\OperationBundle\Entity\Imputation $imputation
     * @return Operation
     */
    public function setImputation(\Finance\OperationBundle\Entity\Imputation $imputation = null)
    {
        $this->imputation = $imputation;

        return $this;
    }

    /**
     * Get imputation
     *
     * @return \Finance\OperationBundle\Entity\Imputation 
     */
    public function getImputation()
    {
        return $this->imputation;
    }

    /**
     * Set stakeholder
     *
     * @param \Finance\OperationBundle\Entity\Stakeholder $stakeholder
     * @return Operation
     */
    public function setStakeholder(\Finance\OperationBundle\Entity\Stakeholder $stakeholder = null)
    {
        $this->stakeholder = $stakeholder;

        return $this;
    }

    /**
     * Get stakeholder
     *
     * @return \Finance\OperationBundle\Entity\Stakeholder 
     */
    public function getStakeholder()
    {
        return $this->stakeholder;
    }

    /**
     * Set paymentMethod
     *
     * @param \Finance\OperationBundle\Entity\PaymentMethod $paymentMethod
     * @return Operation
     */
    public function setPaymentMethod(\Finance\OperationBundle\Entity\PaymentMethod $paymentMethod = null)
    {
        $this->paymentMethod = $paymentMethod;

        return $this;
    }

    /**
     * Get paymentMethod
     *
     * @return \Finance\OperationBundle\Entity\PaymentMethod 
     */
    public function getPaymentMethod()
    {
        return $this->paymentMethod;
    }

    /**
     * Set account
     *
     * @param \Finance\AccountBundle\Entity\Account $account
     * @return Operation
     */
    public function setAccount(\Finance\AccountBundle\Entity\Account $account)
    {
        $this->account = $account;

        return $this;
    }

    /**
     * Get account
     *
     * @return \Finance\AccountBundle\Entity\Account 
     */
    public function getAccount()
    {
        return $this->account;
    }
}
