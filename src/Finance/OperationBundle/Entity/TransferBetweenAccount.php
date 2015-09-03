<?php

namespace Finance\OperationBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * TransferBetweenAccount
 *
 * @ORM\Table(name="finance__operation__transferbetweenaccount")
 * @ORM\Entity(repositoryClass="Finance\OperationBundle\Entity\TransferBetweenAccountRepository")
 */
class TransferBetweenAccount extends AbstractOperation
{
    /**
     * @var Finance\AccountBundle\Entity\Account
     * 
     * Note : Proprietary side
     * @ORM\ManyToOne(targetEntity="Finance\AccountBundle\Entity\Account", inversedBy="outcomeTransfers", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $sourceAccount;
    
    /**
     * @var Finance\AccountBundle\Entity\Account
     * 
     * Note : Proprietary side
     * @ORM\ManyToOne(targetEntity="Finance\AccountBundle\Entity\Account", inversedBy="incomeTranfers", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $destinationAccount;
    
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
     * Set sourceAccount
     *
     * @param \Finance\AccountBundle\Entity\Account $sourceAccount
     * @return TransferBetweenAccount
     */
    public function setSourceAccount(\Finance\AccountBundle\Entity\Account $sourceAccount)
    {
        $this->sourceAccount = $sourceAccount;

        return $this;
    }

    /**
     * Get sourceAccount
     *
     * @return \Finance\AccountBundle\Entity\Account 
     */
    public function getSourceAccount()
    {
        return $this->sourceAccount;
    }

    /**
     * Set destinationAccount
     *
     * @param \Finance\AccountBundle\Entity\Account $destinationAccount
     * @return TransferBetweenAccount
     */
    public function setDestinationAccount(\Finance\AccountBundle\Entity\Account $destinationAccount)
    {
        $this->destinationAccount = $destinationAccount;

        return $this;
    }

    /**
     * Get destinationAccount
     *
     * @return \Finance\AccountBundle\Entity\Account 
     */
    public function getDestinationAccount()
    {
        return $this->destinationAccount;
    }
}
