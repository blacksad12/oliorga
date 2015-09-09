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
     * Toggle mark of an AbstractOperation
     * @Route("/togglemark/")
     * @Method({"POST"})
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     **************************************************************************/
    public function toggleMarkAction()
    {
        $abstractOperation = $this->getDoctrine()
                       ->getRepository('FinanceOperationBundle:AbstractOperation')
                       ->findOneById($this->get('request')->request->get('abstractOperationId'));
        
        if($abstractOperation == NULL) {
            throw new \Exception('AbstractOperation not found!');
        } else {
            $em = $this->getDoctrine()->getManager();
            $abstractOperation->setIsMarked(!$abstractOperation->getIsMarked());
            $em->persist($abstractOperation);
            $em->flush();
            return new \Symfony\Component\HttpFoundation\JsonResponse(array(
                'abstractOperationId'   => $abstractOperation->getId(),
                'isMarked'              => $abstractOperation->getIsMarked()
                ));
        }
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
