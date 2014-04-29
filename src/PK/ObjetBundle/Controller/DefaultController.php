<?php

namespace PK\ObjetBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('PKObjetBundle:Default:index.html.twig', array('name' => $name));
    }
}
