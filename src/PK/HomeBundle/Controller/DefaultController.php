<?php

namespace PK\HomeBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('PKHomeBundle:Default:index.html.twig');
    }
}
