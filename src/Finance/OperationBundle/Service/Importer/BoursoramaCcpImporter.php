<?php

namespace Finance\OperationBundle\Service\Importer;

use Finance\OperationBundle\Entity\Operation;
use Finance\OperationBundle\Entity\TransferBetweenAccount;

/**
 * BoursoramaCcpImporter
 */
class BoursoramaCcpImporter
{
    protected $doctrine;
    protected $stringToEntityIdArray;
    
    /** ************************************************************************
     * Constructor
     * @param $doctrine
     * @param array $stringToEntityIdArray
     **************************************************************************/
    public function __construct($doctrine, $stringToEntityIdArray) {
        $this->doctrine                 = $doctrine;
        $this->stringToEntityIdArray    = $stringToEntityIdArray;
    }
    
    /** ************************************************************************
     * Convert the HTML string containing the copy-paste from Boursorama CCP page
     * into an Operation and TransferBetweenAccount array.
     * @param \Finance\AccountBundle\Entity\Account $account
     * @param string $htmlString
     * @return Operation[]
     **************************************************************************/
    public function processHtmlString(\Finance\AccountBundle\Entity\Account $account, $htmlString) {
        $abstractOperations = array('transferBetweenAccounts' => array(), 'operations' => array() );
        foreach($this->htmlStringToArray($htmlString) as $lineIndex=>$line) {
            if($this->isTransferBetweenAccount($line['label operation'])) {
                $transferBetweenAccount = new TransferBetweenAccount();
                $transferBetweenAccount->setIsMarked(true);
                $transferBetweenAccount->setDate($this->convertDate($line['dateValeur']));
                $transferBetweenAccount->setAmount($this->convertAmount($line['amount']));
                $transferBetweenAccount->setSourceAccount($this->convertSourceAccount($line['label operation']));
                $transferBetweenAccount->setDestinationAccount($this->convertDestinationAccount($line['label operation']));
                $abstractOperations['transferBetweenAccounts'][$lineIndex] = $transferBetweenAccount;
                $abstractOperations['originalHtml']['transferBetweenAccounts'][$lineIndex] = $line['originalHtml'];
            }
            else {
                $operation = new Operation($account);
                $operation->setIsMarked(true);
                $operation->setDate($this->convertDate($line['dateValeur']));
                $operation->setAmount($this->convertAmount($line['amount']));
                $operation->setPaymentMethod($this->convertPaymentMethod($line['label operation']));
                $operation->setStakeholder($this->convertStakeholder($line['label operation']));
                $operation->setCategory($this->convertCategory($line['label operation']));
                $abstractOperations['operations'][$lineIndex] = $operation;
                $abstractOperations['originalHtml']['operations'][$lineIndex] = $line['originalHtml'];
            }
        }
        return $abstractOperations;
    }
    
    /** ************************************************************************
     * Convert a HTML string containing a table into a key=>value array.
     * [0] => [
     *      tdClassName1 => tdContent
     * ]
     * @param string $htmlString
     * @return array
     **************************************************************************/
    protected function htmlStringToArray($htmlString) {
        $DOM = new \DOMDocument();
        $DOM->loadHTML($htmlString);
        $items = $DOM->getElementsByTagName('tr');
        
        $resultArray = array();
        foreach ($items as $key=>$node) {
            foreach ($node->childNodes as $element) {
                if($element->attributes instanceof \DOMNamedNodeMap) {
                    $tdClassName = $element->attributes->item(0)->nodeValue;
                    if (strpos($tdClassName,'amount') !== false) {
                        $tdClassName = 'amount';
                    }
                    $resultArray[$key][$tdClassName]    = $element->nodeValue;
                    $resultArray[$key]['originalHtml']  = $DOM->saveHTML($node);
                }
            }
        }
        return $resultArray;
    }
    
    /** ************************************************************************
     * Return true if the operation identified by $labelOperationString is a
     * TransferBetweenAccount
     * @param string $labelOperationString
     * @return \DateTime
     **************************************************************************/
    protected function isTransferBetweenAccount($labelOperationString) {
        return $this->convertSourceAccount($labelOperationString) !== NULL;
    }
    
    /** ************************************************************************
     * Convert ['dateValeur'] to \DateTime object
     * @param string $dateString
     * @return \DateTime
     **************************************************************************/
    protected function convertDate($dateString) {
        $date = \DateTime::createFromFormat('d/m/Y', $dateString);
        return $date;
    }
    
    /** ************************************************************************
     * Convert ['amount'] to float
     * @param string $amountString
     * @return float
     **************************************************************************/
    protected function convertAmount($amountString) {
        $amount = str_replace(' ', '', $amountString);
        $amount = str_replace('€', '', $amount);
        $amount = str_replace(',', '.', $amount);
        $amount = floatval($amount);
        return $amount;
    }
    
    /** ************************************************************************
     * Convert ['label operation'] to PaymentMethod
     * @param string $labelOperationString
     * @return PaymentMethod
     **************************************************************************/
    protected function convertPaymentMethod($labelOperationString) {
        foreach($this->stringToEntityIdArray['paymentMethod'] as $string=>$paymentMethodId) {
            if(strpos($labelOperationString, $string) !== false) {
                return $this->doctrine->getRepository('FinanceOperationBundle:PaymentMethod')->find($paymentMethodId);
            }
        }
        return NULL;
    }
    
    /** ************************************************************************
     * Convert ['label operation'] to Stakeholder
     * @param string $labelOperationString
     * @return Stakeholder
     **************************************************************************/
    protected function convertStakeholder($labelOperationString) {
        foreach($this->stringToEntityIdArray['stakeholder'] as $string=>$stakeholderId) {
            if(strpos($labelOperationString, $string) !== false) {
                return $this->doctrine->getRepository('FinanceOperationBundle:Stakeholder')->find($stakeholderId);
            }
        }
        return NULL;
    }
    
    /** ************************************************************************
     * Convert ['label operation'] to Category
     * @param string $labelOperationString
     * @return Category
     **************************************************************************/
    protected function convertCategory($labelOperationString) {
        foreach($this->stringToEntityIdArray['category'] as $string=>$categoryId) {
            if(strpos($labelOperationString, $string) !== false) {
                return $this->doctrine->getRepository('FinanceOperationBundle:Category')->find($categoryId);
            }
        }
        return NULL;
    }
    
    /** ************************************************************************
     * Convert ['label operation'] to SourceAccount
     * @param string $labelOperationString
     * @return Account
     **************************************************************************/
    protected function convertSourceAccount($labelOperationString) {
        foreach($this->stringToEntityIdArray['sourceAccount'] as $string=>$sourceAccountId) {
            if(strpos($labelOperationString, $string) !== false) {
                return $this->doctrine->getRepository('FinanceAccountBundle:Account')->find($sourceAccountId);
            }
        }
        return NULL;
    }
    
    /** ************************************************************************
     * Convert ['label operation'] to DestinationAccount
     * @param string $labelOperationString
     * @return Account
     **************************************************************************/
    protected function convertDestinationAccount($labelOperationString) {
        foreach($this->stringToEntityIdArray['destinationAccount'] as $string=>$destinationAccountId) {
            if(strpos($labelOperationString, $string) !== false) {
                return $this->doctrine->getRepository('FinanceAccountBundle:Account')->find($destinationAccountId);
            }
        }
        return NULL;
    }
    
}
