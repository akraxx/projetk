<?php

namespace PK\PretBundle\Controller;

use PK\PretBundle\Entity\Pret;
use PK\UserBundle\Entity\Notification;
use PK\PretBundle\Entity\HashMail;
use PK\PretBundle\Form\PretType;
use PK\ObjetBundle\Entity\Objet;
use PK\ObjetBundle\Form\ObjetType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

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
        
        $repositoryUser = $this->getDoctrine()
                ->getManager()
                ->getRepository('PKUserBundle:User');
        
        $users = $repositoryUser->findAll();
        
        $repository = $this->getDoctrine()
                ->getManager()
                ->getRepository('PKObjetBundle:Objet');
        
        $objets = $repository->findBy(array('actif' => 1, 'proprietaire' => $this->get('security.context')->getToken()->getUser()));
        
        $form = $this->createForm(new PretType(), $pret);
        
        $objet = new Objet();

        $formObjet = $this->createForm(new ObjetType(), $objet);
        
        return $this->render('PKPretBundle:Default:creer.html.twig', array('users' => $users, 'objets' => $objets, 'form' => $form->createView(), 'formObjet' => $formObjet->createView()));
    }
    
    public function creerObjetDefiniAction($id) {
        $pret = new Pret();
        
        $repositoryUser = $this->getDoctrine()
                ->getManager()
                ->getRepository('PKUserBundle:User');
        
        $users = $repositoryUser->findAll();
        
        $repository = $this->getDoctrine()
                ->getManager()
                ->getRepository('PKObjetBundle:Objet');
        
        $objetSelected = $repository->find($id);
        
        $form = $this->createForm(new PretType(), $pret);
        
        $objet = new Objet();

        $formObjet = $this->createForm(new ObjetType(), $objet);
        
        return $this->render('PKPretBundle:Default:creer.html.twig', array('users' => $users, 'objet' => $objetSelected, 'form' => $form->createView(), 'formObjet' => $formObjet->createView()));
    }
    
    public function repreterAction($id) {
        $repositoryUser = $this->getDoctrine()
                ->getManager()
                ->getRepository('PKUserBundle:User');
        
        $users = $repositoryUser->findAll();
        
        $repository = $this->getDoctrine()
                ->getManager()
                ->getRepository('PKPretBundle:Pret');
        
        $pret = $repository->find($id);
        
        $newPret = new Pret();
        $form = $this->createForm(new PretType(), $newPret);
        
        $objet = new Objet();

        $formObjet = $this->createForm(new ObjetType(), $objet);
        
        return $this->render('PKPretBundle:Default:creer.html.twig', array('users' => $users, 'pretParent' => $pret, 'objet' => $pret->getObjet(), 'form' => $form->createView(), 'formObjet' => $formObjet->createView()));
    }
    
    public function listePreteurAction($id) {
        
        $repository = $this->getDoctrine()
                ->getManager()
                ->getRepository('PKPretBundle:Pret');
        
        $pret = $repository->find($id);
        
        $preteurs = array();
        
        $preteurs[$pret->getUser()->getId()] = $pret->getUser()->getUsername();
        
        while($pret->getPretParent() != null) {
            $pret = $pret->getPretParent();
            $preteurs[$pret->getUser()->getId()] = $pret->getUser()->getUsername();
        }
        
        return new Response(json_encode($preteurs));
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
        $repositoryObjets = $em->getRepository('PKObjetBundle:Objet');
        
        $pret = $repository->find($id);
        
        if ($pret == null) {
            $this->get('session')->getFlashBag()->add('error', 'Impossible d\'éditer ce prêt');
            throw $this->createNotFoundException('Pret[id=' . $id . '] inexistant');
        }
        
        $form = $this->createForm(new PretType(), $pret);

        $objets = $repositoryObjets->findBy(array('actif' => 1, 'proprietaire' => $this->get('security.context')->getToken()->getUser()));
        
        $objet = new Objet();

        $formObjet = $this->createForm(new ObjetType(), $objet);
        
        return $this->render('PKPretBundle:Default:editer.html.twig', array('objets' => $objets, 'id' => $id, 'formObjet' => $formObjet->createView(), 'form' => $form->createView()));
    }

    public function renduAction($id, $date = null, $user = null) {
        $request = $this->get('request');
        
        $em = $this->getDoctrine()->getManager();
                
        $repository = $em->getRepository('PKPretBundle:Pret');
        $pret = $repository->find($id);
        
        if ($pret == null) {
            $this->get('session')->getFlashBag()->add('error', 'Impossible de changer le statut de ce prêt');
            throw $this->createNotFoundException('Pret[id=' . $id . '] inexistant');
        }
        
        $currentUser = $this->get('security.context')->getToken()->getUser()->getId();
        
        $pret->setRendu(!$pret->getRendu());
        if($pret->getRendu()) {
            if($user != null) {
                $pretTemp = $pret;
                $found = false;
                while($pretTemp != null && !$found) {
                    $pretTemp->setRendu(true);
                    $pretTemp->setConfirme(false);
                    $pretTemp->setDateRendu(new \DateTime($date));

                    if($pretTemp->getUser()->getId() == $user && $currentUser != $user) {
                        $notification = new Notification();
                        $notification->setDate(new \DateTime());
                        $notification->setMessage("L'objet " . $pret->getObjet()->getTitre() . " vous a été rendu. Ce prêt est maintenant en attente de confirmation");
                        $notification->setUser($pretTemp->getUser());

                        $em->persist($notification);
                        $em->flush();

                        $found = true;
                    }
                    $pretTemp = $pretTemp->getPretParent();
                }
                $pret->setDateRendu(new \DateTime($date));
            }
        }
        else {
            $pret->setDateRendu(null);
        }
        
        if($pret->getUser()->getId() == $currentUser) {
            $pret->setConfirme(true);
        }
        
        $em->persist($pret);
        $em->flush();
        
        if($request->isXmlHttpRequest()) {
            return new Response($pret->toJson());
        }
        else {
            $this->get('session')->getFlashBag()->add('info', 'Changement de statut du prêt effectué');

            return $this->redirect($this->generateUrl('pk_pret_lister'));
        }
        
    }
    
    public function confirmerAction($id) {
        $em = $this->getDoctrine()->getManager();
                
        $repository = $em->getRepository('PKPretBundle:Pret');
        $pret = $repository->find($id);
        
        if ($pret == null) {
            $this->get('session')->getFlashBag()->add('error', 'Impossible de changer le statut de ce prêt');
            throw $this->createNotFoundException('Pret[id=' . $id . '] inexistant');
        }
        
        while($pret != null) {
            $pret->setConfirme(true);

            if($pret->getUserEmprunteur() != null) {
                $notification = new Notification();
                $notification->setDate(new \DateTime());
                $notification->setMessage("Confirmation de reçu de l'objet " . $pret->getObjet()->getTitre() . " par " . $pret->getUser()->getUsername());
                $notification->setUser($pret->getUserEmprunteur());

                $em->persist($notification);
                $em->flush();
            }

            $em->persist($pret);
            $em->flush();
            
            $pret = $pret->getPretEnfant();
        }

        $this->get('session')->getFlashBag()->add('info', 'Rendu du prêt confirmé.');

        return $this->redirect($this->generateUrl('pk_pret_lister'));
    }
    
    public function modifierAction($id) {
        $em = $this->getDoctrine()->getManager();
                
        $repository = $em->getRepository('PKPretBundle:Pret');
        $repositoryObjets = $em->getRepository('PKObjetBundle:Objet');
        
        $pret = $repository->find($id);
        
        $form = $this->createForm(new PretType(), $pret);

        $request = $this->get('request');
        if ($request->getMethod() == 'POST') {
            $form->bind($request);

            $pret->setUser($this->get('security.context')->getToken()->getUser());

            if ($form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $em->persist($pret);
                $em->flush();

                $this->get('session')->getFlashBag()->add(
                        'notice', 'Prêt modifié avec succès.'
                );

                return $this->redirect($this->generateUrl('pk_pret_lister'));
            } else {
                $objet = new Objet();

                $formObjet = $this->createForm(new ObjetType(), $objet);
                $objets = $repositoryObjets->findBy(array('actif' => 1, 'proprietaire' => $this->get('security.context')->getToken()->getUser()));
                return $this->render('PKPretBundle:Default:editer.html.twig', array('objets' => $objets, 'id' => $id, 'formObjet' => $formObjet->createView(), 'form' => $form->createView()));
            }
        }
    }
    
    public function ajouterAction() {
        $pret = new Pret();
        $form = $this->createForm(new PretType(), $pret);
        $repositoryObjets = $this->getDoctrine()
                ->getManager()
                ->getRepository('PKObjetBundle:Objet');
        
        $request = $this->get('request');
        if ($request->getMethod() == 'POST') {
            $form->bind($request);

            $pret->setUser($this->get('security.context')->getToken()->getUser());

            if ($form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $em->persist($pret);
                $em->flush();

                $this->get('session')->getFlashBag()->add(
                        'notice', 'Prêt ajouté à votre liste.'
                );
                if($pret->getTypeEmprunteur() == 2) {
                    try {
                            $this->envoyerMail($pret);
                            $this->get('session')->getFlashBag()->add(
                                'notice', 'Email envoyé à l\'emprunteur.'
                        );
                    }
                    catch(\Exception $e) {
                        $this->get('session')->getFlashBag()->add(
                                'notice', 'Impossible d\'envoyer l\'email à l\'emprunteur.'
                        );
                    }
                }
                // envoie notification
                else if($pret->getTypeEmprunteur() == 1) {
                    $notification = new Notification();
                    $notification->setDate(new \DateTime());
                    $notification->setMessage("L'objet " . $pret->getObjet()->getTitre() . " vous a été prété par " . $pret->getUser()->getUsername());
                    $notification->setUser($pret->getUserEmprunteur());

                    $em->persist($notification);
                    $em->flush();
                }
                return $this->redirect($this->generateUrl('pk_pret_lister'));
            } else {
                $objet = new Objet();

                $formObjet = $this->createForm(new ObjetType(), $objet);
        
                $objets = $repositoryObjets->findBy(array('actif' => 1, 'proprietaire' => $this->get('security.context')->getToken()->getUser()));
                return $this->render('PKPretBundle:Default:creer.html.twig', array('objets' => $objets, 'formObjet' => $formObjet->createView(), 'form' => $form->createView()));
            }
        }
    }
    
    public function envoyerMail(Pret $pret) {
        $hashMail = new HashMail();
        $hashMail->setMail($pret->getEmailEmprunteur());
        $hashMail->setHashcode(md5($pret->getEmailEmprunteur()));
        
        $em = $this->getDoctrine()->getManager();
        $em->persist($hashMail);
        $em->flush();
        
        $message = \Swift_Message::newInstance()
        ->setSubject('[PROJET-K] Nouvel objet prété ')
        ->setTo($pret->getEmailEmprunteur())
        ->setFrom('noreply@projet-k.fr')
        ->setBody(
            $this->renderView(
                'PKPretBundle:Email:email.html.twig',
                array('pret' => $pret, 'hashcode' => $hashMail->getHashcode())
            ), "text/html"
        );
        $this->get('mailer')->send($message);
    }

}
