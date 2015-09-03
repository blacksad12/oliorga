<?php

namespace Finance\OperationBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Finance\OperationBundle\Entity\AbstractOperation;
use Finance\OperationBundle\Form\AbstractOperationType;

/**
 * AbstractOperation controller.
 *
 * @Route("/abstractoperation")
 */
class AbstractOperationController extends Controller
{
    /** ************************************************************************
     * Display the AbstractOperation's homepage.
     * @Route("/")
     **************************************************************************/
    public function homeAction()        
    {
        $abstractoperations = $this->getDoctrine()
                ->getRepository('FinanceOperationBundle:AbstractOperation')
                ->findAll();
        
        return $this->render('FinanceOperationBundle:AbstractOperation:home.html.twig', array(
            'abstractoperations' => $abstractoperations
        ));
    }

    /** ************************************************************************
     * Create a new AbstractOperation according to the information given in the form.
     * @Route("/add")
     **************************************************************************/
    public function addAction()        
    {
        $abstractoperation = new AbstractOperation();
        
        $form = $this->createForm(new AbstractOperationType(), $abstractoperation);

        // ------------- Request Management ------------------------------------
        $request = $this->get('request');
        if ($request->getMethod() == 'POST') {
          $form->bind($request);// Link Request and Form

          if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($abstractoperation);
            $em->flush();

            return $this->redirect($this->generateUrl('finance_operation_abstractoperation_see', array('abstractoperation_id' => $abstractoperation->getId())));
          }
        }

        return $this->render('FinanceOperationBundle:AbstractOperation:add.html.twig', array(
            'form' => $form->createView(),
        ));
    }
    
    /** ************************************************************************
     * Display a AbstractOperation
     * @param AbstractOperation $abstractoperation
     * @ParamConverter("abstractoperation", options={"mapping": {"abstractoperation_id": "id"}})
     * @Route("/see/{abstractoperation_id}", requirements={"abstractoperation_id" = "\d+"})
     **************************************************************************/
    public function seeAction(AbstractOperation $abstractoperation)
    {
        
        return $this->render('FinanceOperationBundle:AbstractOperation:see.html.twig', array(
            'abstractoperation'      => $abstractoperation,            
          ));
    }
    
    /** ************************************************************************
     * Modify a AbstractOperation according to the information given in the form.
     * 
     * @param AbstractOperation $abstractoperation
     * @ParamConverter("abstractoperation", options={"mapping": {"abstractoperation_id": "id"}})
     * @Route("/modify/{abstractoperation_id}", requirements={"abstractoperation_id" = "\d+"})
     **************************************************************************/
    public function modifyAction(AbstractOperation $abstractoperation)
    {
        $form = $this->createForm(new AbstractOperationType($abstractoperation), $abstractoperation);

        // ------------- Request Management ------------------------------------
        $request = $this->get('request');
        if ($request->getMethod() == 'POST') {
          $form->bind($request); // Link Request and Form

          if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($abstractoperation);
            $em->flush();

            return $this->redirect($this->generateUrl('finance_operation_abstractoperation_see', array('abstractoperation_id' => $abstractoperation->getId())));
          }
        }

        return $this->render('FinanceOperationBundle:AbstractOperation:modify.html.twig', array(
            'abstractoperation' => $abstractoperation,
            'form' => $form->createView(),           
        ));
    }
    
    /** ************************************************************************
     * Delete a AbstractOperation.
     * 
     * @param AbstractOperation $abstractoperation
     * @ParamConverter("abstractoperation", options={"mapping": {"abstractoperation_id": "id"}})
     * @Route("/delete/{abstractoperation_id}", requirements={"abstractoperation_id" = "\d+"})
     **************************************************************************/
    public function deleteAction(AbstractOperation $abstractoperation)
    {
        $request = $this->get('request');
        if ($request->getMethod() == 'POST') {
            $em = $this->getDoctrine()->getManager();
            $em->remove($abstractoperation);
            $em->flush();
            return $this->redirect($this->generateUrl(/* Redirect to some page */));          
        }
        else{
            throw new \Symfony\Component\Security\Core\Exception\AccessDeniedException;
        }
    }
    
}
