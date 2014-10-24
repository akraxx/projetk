<?php

namespace PK\PretBundle\Controller;

use PK\PretBundle\Entity\Pret;
use PK\UserBundle\Entity\Notification;
use PK\PretBundle\Form\PretType;
use PK\ObjetBundle\Entity\Objet;
use PK\ObjetBundle\Form\ObjetType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class EmpruntController extends Controller {

    public function listerAction() {
        $repository = $this->getDoctrine()
                ->getManager()
                ->getRepository('PKPretBundle:Pret');

        $prets = $repository->findBy(array('userEmprunteur' => $this->get('security.context')->getToken()->getUser()));

        return $this->render('PKPretBundle:Emprunt:lister.html.twig', array('prets' => $prets));
    }
    
    private function rendu($id, $date = null, $user = null)  {
        $em = $this->getDoctrine()->getManager();
                
        $repository = $em->getRepository('PKPretBundle:Pret');
        $pret = $repository->find($id);
        
        if ($pret == null) {
            $this->get('session')->getFlashBag()->add('error', 'Impossible de changer le statut de ce prêt');
            throw $this->createNotFoundException('Pret[id=' . $id . '] inexistant');
        }
        
        $pret->setRendu(!$pret->getRendu());
        if($pret->getRendu()) {
            if($user != null) {
                $pretTemp = $pret;
                $found = false;
                while($pretTemp != null && !$found) {
                    $pretTemp->setRendu(true);
                    $pretTemp->setConfirme(false);
                    $pretTemp->setDateRendu(new \DateTime($date));

                    if($pretTemp->getUser()->getId() == $user) {
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

        $em->persist($pret);
        $em->flush();
    }
    
    public function renduAction($id, $date = null, $user = null) {
        $this->rendu($id, $date, $user);

        $this->get('session')->getFlashBag()->add('info', 'Changement de statut du prêt effectué');

        return $this->redirect($this->generateUrl('pk_pret_lister_emprunt'));

    }
    
    public function renduMailAction($id, $date, $hashcode, $user = null) {
        $this->rendu($id, $date, $user);
        
        $this->get('session')->getFlashBag()->add('info', 'Changement de statut du prêt effectué');

        return $this->redirect($this->generateUrl('pk_pret_lister_emprunt_email', array('hashcode' => $hashcode)));
    }
    
    public function listerParEmailAction($hashcode) {
        $em = $this->getDoctrine()
                ->getManager();
        
        $hashMail = $em->getRepository('PKPretBundle:HashMail')->findOneBy(array('hashcode' => $hashcode));
        $repository = $this->getDoctrine()
                ->getManager()
                ->getRepository('PKPretBundle:Pret');
        
        if($hashMail != null) {
            $prets = $repository->findBy(array('emailEmprunteur' => $hashMail->getMail()));

            return $this->render('PKPretBundle:Emprunt:lister.html.twig', array('prets' => $prets, 'hashcode' => $hashcode));
        }
        else {
            $this->get('session')->getFlashBag()->add('error', 'Aucun mail n\'est lié à ce code');
            
            return $this->redirect($this->generateUrl('pk_home_homepage'));
        }
    }
    
}
