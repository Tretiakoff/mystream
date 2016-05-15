<?php

namespace MyStreamBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('MyStreamBundle:Default:index.html.twig');
    }
}
