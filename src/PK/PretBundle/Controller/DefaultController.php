<?php

namespace PK\PretBundle\Controller;

use PK\PretBundle\Entity\Pret;
use PK\PretBundle\Form\PretType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller {

    public function listerAction() {
        $repository = $this->getDoctrine()
                ->getManager()
                ->getRepository('PKPretBundle:Pret');

        $prets = $repository->findBy(array('user' => $this->get('security.context')->getToken()->getUser()));

        return $this->render('PKPretBundle:Default:lister.html.twig', array('prets' => $prets));
    }

    public function creerAction() {
        $pret = new Pret();

        $form = $this->createForm(new PretType(), $pret);

        return $this->render('PKPretBundle:Default:creer.html.twig', array('form' => $form->createView()));
    }

    public function supprimerAction($id) {
        $em = $this->getDoctrine()->getManager();
                
        $repository = $em->getRepository('PKPretBundle:Pret');
        $pret = $repository->find($id);

        if ($pret == null) {
            $this->get('session')->getFlashBag()->add('error', 'Impossible de supprimer ce prêt');
            throw $this->createNotFoundException('Pret[id=' . $id . '] inexistant');
        }
        
        $em->remove($pret);
        $em->flush();
        
        $this->get('session')->getFlashBag()->add('info', 'Prêt supprimé');

        return $this->redirect($this->generateUrl('pk_pret_lister'));
    }

    public function editerAction($id) {
        $em = $this->getDoctrine()->getManager();
                
        $repository = $em->getRepository('PKPretBundle:Pret');
        $pret = $repository->find($id);
        
        if ($pret == null) {
            $this->get('session')->getFlashBag()->add('error', 'Impossible d\'éditer ce prêt');
            throw $this->createNotFoundException('Pret[id=' . $id . '] inexistant');
        }
        
        $form = $this->createForm(new PretType(), $pret);

        return $this->render('PKPretBundle:Default:editer.html.twig', array('id' => $id, 'form' => $form->createView()));
    }

    public function renduAction($id) {
        $em = $this->getDoctrine()->getManager();
                
        $repository = $em->getRepository('PKPretBundle:Pret');
        $pret = $repository->find($id);
        
        if ($pret == null) {
            $this->get('session')->getFlashBag()->add('error', 'Impossible de changer le statut de ce prêt');
            throw $this->createNotFoundException('Pret[id=' . $id . '] inexistant');
        }
        
        $pret->setRendu(!$pret->getRendu());
        if($pret->getRendu()) {
            $pret->setDateRendu(new \DateTime());
        }
        else {
            $pret->setDateRendu(null);
        }
        $em->persist($pret);
        $em->flush();
        
        $this->get('session')->getFlashBag()->add('info', 'Changement de statut du prêt effectué');

        return $this->redirect($this->generateUrl('pk_pret_lister'));
    }
    
    public function modifierAction($id) {
        $em = $this->getDoctrine()->getManager();
                
        $repository = $em->getRepository('PKPretBundle:Pret');
        $pret = $repository->find($id);
        
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
                        'notice', 'Prêt modifié avec succès.'
                );

                return $this->redirect($this->generateUrl('pk_pret_lister'));
            } else {
                return $this->render('PKPretBundle:Default:editer.html.twig', array('id' => $id, 'form' => $form->createView()));
            }
        }
    }
    
    public function ajouterAction() {
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
                        'notice', 'Prêt ajouté à votre liste.'
                );

                return $this->redirect($this->generateUrl('pk_pret_lister'));
            } else {
                return $this->render('PKPretBundle:Default:creer.html.twig', array('form' => $form->createView()));
            }
        }
    }

}
