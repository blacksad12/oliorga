<?php

namespace Finance\OperationBundle\Service\Helper;

use Finance\OperationBundle\Entity\TransferBetweenAccount;

/**
 * TransferBetweenAccountHelper
 */
class TransferBetweenAccountHelper extends AbstractOperationHelper
{
    /** ************************************************************************
     * Duplicate a TransferBetweenAccount
     * 
     * @param \Finance\OperationBundle\Entity\TransferBetweenAccount $oldTransferBetweenAccount
     * @param \Finance\OperationBundle\Entity\TransferBetweenAccount $newTransferBetweenAccount
     **************************************************************************/
    public function duplicate(TransferBetweenAccount $oldTransferBetweenAccount, TransferBetweenAccount $newTransferBetweenAccount) {
        parent::duplicateAbstractOperation($oldTransferBetweenAccount, $newTransferBetweenAccount);        
        $newTransferBetweenAccount->setSourceAccount($oldTransferBetweenAccount->getSourceAccount());
        $newTransferBetweenAccount->setDestinationAccount($oldTransferBetweenAccount->getDestinationAccount());
    }
    
}
