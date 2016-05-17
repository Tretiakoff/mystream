<?php

namespace MyStreamBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        $url = $this->getDoctrine()
            ->getRepository('EntityBundle:Video')
            ->findBy(array(), array('id' => 'desc'));

        return $this->render('MyStreamBundle:Default:index.html.twig', array('url' => $url));
    }
    
}

