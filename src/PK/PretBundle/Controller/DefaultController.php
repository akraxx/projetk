<?php

namespace PK\PretBundle\Controller;

use PK\PretBundle\Entity\Pret;
use PK\PretBundle\Form\PretType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function listerAction() 
    {
        $repository = $this->getDoctrine()
                ->getManager()
                ->getRepository('PKPretBundle:Pret');
        
        $prets = $repository->findBy(array('user' => $this->get('security.context')->getToken()->getUser()));
        
        return $this->render('PKPretBundle:Default:lister.html.twig', array('prets' => $prets));
    }
    
    public function creerAction()
    {
        $pret = new Pret();
        
        $form = $this->createForm(new PretType(), $pret);
        
        return $this->render('PKPretBundle:Default:creer.html.twig', array('form' => $form->createView()));
    }
    
    public function supprimerAction($id)
    {
        $pret = new Pret();
        
        $form = $this->createForm(new PretType(), $pret);
        
        return $this->render('PKPretBundle:Default:creer.html.twig', array('form' => $form->createView()));
    }
    
    public function editerAction($id)
    {
        $pret = new Pret();
        
        $form = $this->createForm(new PretType(), $pret);
        
        return $this->render('PKPretBundle:Default:creer.html.twig', array('form' => $form->createView()));
    }
    
    public function renduAction($id)
    {
        $pret = new Pret();
        
        $form = $this->createForm(new PretType(), $pret);
        
        return $this->render('PKPretBundle:Default:creer.html.twig', array('form' => $form->createView()));
    }
    
    public function ajouterAction()
    {
        $pret = new Pret();
        $form = $this->createForm(new PretType(), $pret);

        $request = $this->get('request');
        if ($request->getMethod() == 'POST') {
            $form->bind($request);
            
            $pret->setUser($this->get('security.context')->getToken()->getUser());
            
            if ($form->isValid()) {
                //$pret->setDate($form->get('date')->getData());
                $em = $this->getDoctrine()->getManager();
                $em->persist($pret);
                $em->flush();
                
                $this->get('session')->getFlashBag()->add(
                    'notice',
                    'Prêt ajouté à votre liste.'
                );
                    
                return $this->redirect($this->generateUrl('pk_pret_lister'));
            }
            else {
                return $this->render('PKPretBundle:Default:creer.html.twig', array('form' => $form->createView()));
            }
        }
    }
    
    
}
