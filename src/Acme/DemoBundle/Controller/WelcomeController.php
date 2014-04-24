<?php

namespace Acme\DemoBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class WelcomeController extends Controller
{
    public function indexAction()
    {
        /*
         * The action's view can be rendered using render() method
         * or @Template annotation as demonstrated in DemoController.
         *
         */
        $array = array();
        if($this->get('security.context')->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
            $response = file_get_contents('http://graph.facebook.com/'.$this->get('security.context')->getToken()->getUser()->getUsername());
            $array = json_decode($response, true);
        }
        

        return $this->render('AcmeDemoBundle:Welcome:index.html.twig', array("facebook_infos" => $array));
    }
}
