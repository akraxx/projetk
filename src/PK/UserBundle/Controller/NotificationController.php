<?php

namespace PK\UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class NotificationController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('PKUserBundle:Default:index.html.twig', array('name' => $name));
    }
    
    public function luAction($id)
    {
        $repository = $this->getDoctrine()
                ->getManager()
                ->getRepository('PKUserBundle:Notification');
        
        $repository->luNotification($id);
        
        $this->get('session')->getFlashBag()->add(
            'notice', 'Notification marquée comme lue.'
        );
        
        return $this->redirect($this->generateUrl('pk_user_notifications_lister'));
    }
    
    public function toutLuAction()
    {
        $repository = $this->getDoctrine()
                ->getManager()
                ->getRepository('PKUserBundle:Notification');
        
        $repository->luTouteNotification($this->get('security.context')->getToken()->getUser());
        
        $this->get('session')->getFlashBag()->add(
            'notice', 'Toutes les notifications marquées comme lue.'
        );
        
        return $this->redirect($this->generateUrl('pk_user_notifications_lister'));
    }
    
    public function listerAction()
    {
        $repository = $this->getDoctrine()
                ->getManager()
                ->getRepository('PKUserBundle:Notification');

        $notifications = $repository->findBy(array('user' => $this->get('security.context')->getToken()->getUser()), array('date' => 'desc'));
        
        return $this->render('PKUserBundle:Notification:lister.html.twig', array('notifications' => $notifications));
    }
    
    public function menuAction()
    {
        $repository = $this->getDoctrine()
                ->getManager()
                ->getRepository('PKUserBundle:Notification');

        $notifications = $repository->findBy(array('user' => $this->get('security.context')->getToken()->getUser(), 'lu' => 0));
        
        return $this->render('PKUserBundle:Notification:menu.html.twig', array('notifications' => $notifications));
    }
    
    public function titleAction()
    {
        $repository = $this->getDoctrine()
                ->getManager()
                ->getRepository('PKUserBundle:Notification');

        $notifications = $repository->findBy(array('user' => $this->get('security.context')->getToken()->getUser(), 'lu' => 0));
        
        return $this->render('PKUserBundle:Notification:title.html.twig', array('notifications' => $notifications));
    }
}
