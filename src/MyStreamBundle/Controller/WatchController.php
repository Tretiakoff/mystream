<?php
namespace MyStreamBundle\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
class WatchController extends Controller
{
    public function indexAction($videoId)
    {
        $video = $this->getDoctrine()
            ->getRepository('EntityBundle:Video')
            ->findOneById($videoId);
        $author = $this->getDoctrine()
            ->getRepository('EntityBundle:User')
            ->findOneById($video->getAuthorId());
        return $this->render('MyStreamBundle:Watch:index.html.twig', array('video' => $video, 'author' => $author));
    }
}
