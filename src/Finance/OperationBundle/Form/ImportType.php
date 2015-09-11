<?php

namespace Finance\OperationBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ImportType extends AbstractType
{
    private $router;
    private $account;
    
    public function __construct(\Symfony\Component\Routing\Router $router, \Finance\AccountBundle\Entity\Account $account=NULL) {
        $this->router   = $router;
        $this->account  = $account;
    }

    /** ************************************************************************
     * @param FormBuilderInterface $builder
     * @param array $options
     **************************************************************************/
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('accountId', 'hidden', array(
                'mapped'        => false,
                ))
            ->add('htmlString', 'hidden', array(
                'mapped'        => false,
                ))
            ->add('operationList', 'collection', array(
                'type'          => new OperationType(),
                'mapped'        => false,
                'label'         => NULL,
                'allow_add'     => true,
                'allow_delete'  => true,                
                ))
            ->add('transferBetweenAccountList', 'collection', array(
                'type'          => new TransferBetweenAccountType(),
                'mapped'        => false,
                'label'         => NULL,
                'allow_add'     => true,
                'allow_delete'  => true,                
                ))
            ;
        if ($this->account !== NULL) {
            $builder->setAction($this->router->generate('finance_operation_operation_persistimportform', array('account_id' => $this->account->getId())));            
        }
    }
    
    /** ************************************************************************
     * @return string
     **************************************************************************/
    public function getName() {
        return 'finance_operationbundle_import';
    }
    
}
