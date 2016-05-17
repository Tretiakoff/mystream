<?php

namespace MyStreamBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class ChannelController extends Controller
{
    public function indexAction($username)
    {
        $user = $this->getDoctrine()
            ->getRepository('EntityBundle:User')
            ->findOneByusername($username);
        $video = $this->getDoctrine()
            ->getRepository('EntityBundle:Video')
            ->findBy(array('authorId'=>$user->getId()), array('id' => 'desc'));
        return $this->render('MyStreamBundle:Channel:index.html.twig', array('user'=>$user, 'video'=>$video));
    }
}
