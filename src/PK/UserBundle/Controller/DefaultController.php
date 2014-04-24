<?php

namespace PK\UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('PKUserBundle:Default:index.html.twig', array('name' => $name));
    }
}
