<?php

namespace Finance\OperationBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Finance\OperationBundle\Entity\Stakeholder;
use Finance\OperationBundle\Form\StakeholderType;

/**
 * Stakeholder controller.
 *
 * @Route("/stakeholder")
 */
class StakeholderController extends Controller
{
    /** ************************************************************************
     * Display the Stakeholder's homepage.
     * @Route("/")
     **************************************************************************/
    public function homeAction()        
    {
        $stakeholders = $this->getDoctrine()
                ->getRepository('FinanceOperationBundle:Stakeholder')
                ->findAll();
        
        return $this->render('FinanceOperationBundle:Stakeholder:home.html.twig', array(
            'stakeholders' => $stakeholders
        ));
    }

    /** ************************************************************************
     * Create a new Stakeholder according to the information given in the form.
     * @Route("/add")
     **************************************************************************/
    public function addAction()        
    {
        $stakeholder = new Stakeholder();
        
        $form = $this->createForm(new StakeholderType(), $stakeholder);

        // ------------- Request Management ------------------------------------
        $request = $this->get('request');
        if ($request->getMethod() == 'POST') {
          $form->bind($request);// Link Request and Form

          if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($stakeholder);
            $em->flush();

            return $this->redirect($this->generateUrl('finance_operation_stakeholder_see', array('stakeholder_id' => $stakeholder->getId())));
          }
        }

        return $this->render('FinanceOperationBundle:Stakeholder:add.html.twig', array(
            'form' => $form->createView(),
        ));
    }
    
    /** ************************************************************************
     * Display a Stakeholder
     * @param Stakeholder $stakeholder
     * @ParamConverter("stakeholder", options={"mapping": {"stakeholder_id": "id"}})
     * @Route("/see/{stakeholder_id}", requirements={"stakeholder_id" = "\d+"})
     **************************************************************************/
    public function seeAction(Stakeholder $stakeholder)
    {
        
        return $this->render('FinanceOperationBundle:Stakeholder:see.html.twig', array(
            'stakeholder'      => $stakeholder,            
          ));
    }
    
    /** ************************************************************************
     * Modify a Stakeholder according to the information given in the form.
     * 
     * @param Stakeholder $stakeholder
     * @ParamConverter("stakeholder", options={"mapping": {"stakeholder_id": "id"}})
     * @Route("/modify/{stakeholder_id}", requirements={"stakeholder_id" = "\d+"})
     **************************************************************************/
    public function modifyAction(Stakeholder $stakeholder)
    {
        $form = $this->createForm(new StakeholderType($stakeholder), $stakeholder);

        // ------------- Request Management ------------------------------------
        $request = $this->get('request');
        if ($request->getMethod() == 'POST') {
          $form->bind($request); // Link Request and Form

          if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($stakeholder);
            $em->flush();

            return $this->redirect($this->generateUrl('finance_operation_stakeholder_see', array('stakeholder_id' => $stakeholder->getId())));
          }
        }

        return $this->render('FinanceOperationBundle:Stakeholder:modify.html.twig', array(
            'stakeholder' => $stakeholder,
            'form' => $form->createView(),           
        ));
    }
    
    /** ************************************************************************
     * Delete a Stakeholder.
     * 
     * @param Stakeholder $stakeholder
     * @ParamConverter("stakeholder", options={"mapping": {"stakeholder_id": "id"}})
     * @Route("/delete/{stakeholder_id}", requirements={"stakeholder_id" = "\d+"})
     **************************************************************************/
    public function deleteAction(Stakeholder $stakeholder)
    {
        $request = $this->get('request');
        if ($request->getMethod() == 'POST') {
            $em = $this->getDoctrine()->getManager();
            $em->remove($stakeholder);
            $em->flush();
            return $this->redirect($this->generateUrl(/* Redirect to some page */));          
        }
        else{
            throw new \Symfony\Component\Security\Core\Exception\AccessDeniedException;
        }
    }
    
}
