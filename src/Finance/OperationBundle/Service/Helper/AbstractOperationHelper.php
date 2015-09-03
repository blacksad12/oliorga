<?php

namespace Finance\OperationBundle\Service\Helper;

use Finance\OperationBundle\Entity\AbstractOperation;

/**
 * AbstractOperationHelper
 */
class AbstractOperationHelper 
{
    /** ************************************************************************
     * Duplicate an AbstractOperation, except isMarked fields
     * 
     * @param \Finance\OperationBundle\Entity\AbstractOperation $oldAbstractOperation
     * @param \Finance\OperationBundle\Entity\AbstractOperation $newAbstractOperation
     **************************************************************************/
    public function duplicateAbstractOperation(AbstractOperation $oldAbstractOperation, AbstractOperation $newAbstractOperation)
    {
        $newAbstractOperation->setDate($oldAbstractOperation->getDate());
        $newAbstractOperation->setAmount($oldAbstractOperation->getAmount());        
    }
}
