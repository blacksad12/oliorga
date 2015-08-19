<?php

namespace Nutri\IngredientBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Nutri\IngredientBundle\Entity\Aliment;
use Nutri\IngredientBundle\Form\AlimentType;

/**
 * Aliment controller.
 *
 * @Route("/aliment")
 */
class AlimentController extends Controller
{
    /** ************************************************************************
     * Display the Aliment's homepage.
     * @Route("/")
     **************************************************************************/
    public function homeAction()        
    {
        $aliments = $this->getDoctrine()
                ->getRepository('NutriIngredientBundle:Aliment')
                ->findAll();
        
        return $this->render('NutriIngredientBundle:Aliment:home.html.twig', array(
            'aliments' => $aliments
        ));
    }

    /** ************************************************************************
     * Create a new Aliment according to the information given in the form.
     * @Route("/add")
     **************************************************************************/
    public function addAction()        
    {
        $aliment = new Aliment();
        
        $form = $this->createForm(new AlimentType(), $aliment);

        // ------------- Request Management ------------------------------------
        $request = $this->get('request');
        if ($request->getMethod() == 'POST') {
          $form->bind($request);// Link Request and Form

          if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($aliment);
            $em->flush();

            return $this->redirect($this->generateUrl('nutri_ingredient_aliment_see', array('aliment_id' => $aliment->getId())));
          }
        }

        return $this->render('NutriIngredientBundle:Aliment:add.html.twig', array(
            'form' => $form->createView(),
        ));
    }
    
    /** ************************************************************************
     * Display a Aliment
     * @param Aliment $aliment
     * @ParamConverter("aliment", options={"mapping": {"aliment_id": "id"}})
     * @Route("/see/{aliment_id}", requirements={"aliment_id" = "\d+"})
     **************************************************************************/
    public function seeAction(Aliment $aliment)
    {
        
        return $this->render('NutriIngredientBundle:Aliment:see.html.twig', array(
            'aliment'      => $aliment,            
          ));
    }
    
    /** ************************************************************************
     * Modify a Aliment according to the information given in the form.
     * 
     * @param Aliment $aliment
     * @ParamConverter("aliment", options={"mapping": {"aliment_id": "id"}})
     * @Route("/modify/{aliment_id}", requirements={"aliment_id" = "\d+"})
     **************************************************************************/
    public function modifyAction(Aliment $aliment)
    {
        $form = $this->createForm(new AlimentType($aliment), $aliment);

        // ------------- Request Management ------------------------------------
        $request = $this->get('request');
        if ($request->getMethod() == 'POST') {
          $form->bind($request); // Link Request and Form

          if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($aliment);
            $em->flush();

            return $this->redirect($this->generateUrl('nutri_ingredient_aliment_see', array('aliment_id' => $aliment->getId())));
          }
        }

        return $this->render('NutriIngredientBundle:Aliment:modify.html.twig', array(
            'aliment' => $aliment,
            'form' => $form->createView(),           
        ));
    }
    
    /** ************************************************************************
     * Delete a Aliment.
     * 
     * @param Aliment $aliment
     * @ParamConverter("aliment", options={"mapping": {"aliment_id": "id"}})
     * @Route("/delete/{aliment_id}", requirements={"aliment_id" = "\d+"})
     **************************************************************************/
    public function deleteAction(Aliment $aliment)
    {
        $request = $this->get('request');
        if ($request->getMethod() == 'POST') {
            $em = $this->getDoctrine()->getManager();
            $em->remove($aliment);
            $em->flush();
            return $this->redirect($this->generateUrl(/* Redirect to some page */));          
        }
        else{
            throw new \Symfony\Component\Security\Core\Exception\AccessDeniedException;
        }
    }
    
}
