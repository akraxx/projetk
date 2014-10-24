<?php

namespace PK\ObjetBundle\Controller;

use PK\ObjetBundle\Entity\Objet;
use PK\ObjetBundle\Form\ObjetType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class DefaultController extends Controller
{
    public function listertoutAction() {
        $repository = $this->getDoctrine()
                ->getManager()
                ->getRepository('PKObjetBundle:Objet');

        $objets = $repository->findBy(array('actif' => true), array('titre' => 'asc'));

        return $this->render('PKObjetBundle:Default:listertout.html.twig', array('objets' => $objets));
    }
    
    public function listerAction() {
        $repository = $this->getDoctrine()
                ->getManager()
                ->getRepository('PKObjetBundle:Objet');

        $objets = $repository->findBy(array('proprietaire' => $this->get('security.context')->getToken()->getUser()));

        return $this->render('PKObjetBundle:Default:lister.html.twig', array('objets' => $objets));
    }

    public function creerAction() {
        $objet = new Objet();

        $form = $this->createForm(new ObjetType(), $objet);

        return $this->render('PKObjetBundle:Default:creer.html.twig', array('form' => $form->createView()));
    }
    
    public function rechercherEmptyAction() {
        $repository = $this->getDoctrine()
                ->getManager()
                ->getRepository('PKObjetBundle:Objet');

        $objets = $repository->findBy(array('actif' => true), array('titre' => 'asc'));

        return $this->render('PKObjetBundle:Default:listertout_content.html.twig', array('objets' => $objets));
    }
    
    public function rechercherAction($search) {
        $repository = $this->getDoctrine()
                ->getManager()
                ->getRepository('PKObjetBundle:Objet');

        $objets = $repository->getObjetsBySearchCriteria($search);

        return $this->render('PKObjetBundle:Default:listertout_content.html.twig', array('objets' => $objets, 'search' => $search));
    }
    
    public function ajouterAction() {
        $request = $this->get('request');
        
        $objet = new Objet();
        $form = $this->createForm(new ObjetType(), $objet);

        if ($request->getMethod() == 'POST') {
            $form->bind($request);

            $objet->setProprietaire($this->get('security.context')->getToken()->getUser());

            if ($form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $em->persist($objet);
                $em->flush();

                if($request->isXmlHttpRequest()) {
                    return new Response($objet->toJson());
                }
                else {
                    $this->get('session')->getFlashBag()->add(
                        'notice', 'Objet ajouté à votre liste.'
                    );
                }
                return $this->redirect($this->generateUrl('pk_objet_lister'));
            } else if($request->isXmlHttpRequest()) {
                return new Response("ko");
            } else {
                return $this->render('PKObjetBundle:Default:creer.html.twig', array('form' => $form->createView()));
            }
        }
    }
    
    public function editerAction($id) {
        $em = $this->getDoctrine()->getManager();
                
        $repository = $em->getRepository('PKObjetBundle:Objet');
        $objet = $repository->find($id);
        
        if ($objet == null) {
            $this->get('session')->getFlashBag()->add('error', 'Impossible d\'éditer cet objet');
            throw $this->createNotFoundException('Objet[id=' . $id . '] inexistant');
        }
        
        $form = $this->createForm(new ObjetType(), $objet);

        return $this->render('PKObjetBundle:Default:editer.html.twig', array('id' => $id, 'form' => $form->createView()));
    }
    
    public function pretableAction($id) {
        $em = $this->getDoctrine()->getManager();
                
        $repository = $em->getRepository('PKObjetBundle:Objet');
        $objet = $repository->getObjetAvecPretEnCours($id);
        if($objet != null) {
            return new Response($objet->toJson());
        }
        else {
            return new Response();
        }
    }
    
    public function modifierAction($id) {
        $em = $this->getDoctrine()->getManager();
                
        $repository = $em->getRepository('PKObjetBundle:Objet');
        $objet = $repository->find($id);
        
        $form = $this->createForm(new ObjetType(), $objet);

        $request = $this->get('request');
        if ($request->getMethod() == 'POST') {
            $form->bind($request);

            $objet->setProprietaire($this->get('security.context')->getToken()->getUser());

            if ($form->isValid()) {
                //$pret->setDate($form->get('date')->getData());
                $em = $this->getDoctrine()->getManager();
                $em->persist($objet);
                $em->flush();

                $this->get('session')->getFlashBag()->add(
                        'notice', 'Objet modifié avec succès.'
                );

                return $this->redirect($this->generateUrl('pk_objet_lister'));
            } else {
                return $this->render('PKObjetBundle:Default:editer.html.twig', array('id' => $id, 'form' => $form->createView()));
            }
        }
    }
    
    public function supprimerAction($id) {
        $em = $this->getDoctrine()->getManager();
                
        $repository = $em->getRepository('PKObjetBundle:Objet');
        $objet = $repository->find($id);

        if ($objet == null) {
            $this->get('session')->getFlashBag()->add('error', 'Impossible de supprimer cet objet');
            throw $this->createNotFoundException('Objet[id=' . $id . '] inexistant');
        }
        
        $em->remove($objet);
        $em->flush();
        
        $this->get('session')->getFlashBag()->add('info', 'Objet supprimé');

        return $this->redirect($this->generateUrl('pk_objet_lister'));
    }
    
    public function actifAction($id) {
        $em = $this->getDoctrine()->getManager();
                
        $repository = $em->getRepository('PKObjetBundle:Objet');
        $objet = $repository->find($id);
        
        if ($objet == null) {
            $this->get('session')->getFlashBag()->add('error', 'Impossible de changer le statut de cet objet');
            throw $this->createNotFoundException('Objet[id=' . $id . '] inexistant');
        }
        
        $objet->setActif(!$objet->getActif());;
        
        $em->persist($objet);
        $em->flush();
        
        $this->get('session')->getFlashBag()->add('info', 'Changement de statut de l\'objet effectué');

        return $this->redirect($this->generateUrl('pk_objet_lister'));
    }
}
