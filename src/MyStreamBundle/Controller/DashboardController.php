<?php

namespace MyStreamBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use EntityBundle\Entity\Video;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use MyStreamBundle\Form\Type\UploadVideoType;
use MyStreamBundle\Form\Type\UploadVideoUrlType;
use Symfony\Component\HttpFoundation\Response;
use Vich\UploaderBundle\Form\Type\VichFileType;

class DashboardController extends Controller
{
    public function indexAction()
    {
        if ($this->container->get('security.context')->isGranted('IS_AUTHENTICATED_FULLY')) {
            $user = $this->getDoctrine()
                ->getRepository('EntityBundle:User')
                ->findOneById($this->container->get('security.context')->getToken()->getUser()->getId());

            $url = $this->getDoctrine()
                ->getRepository('EntityBundle:Video')
                ->findBy(array('authorId' => $user->getId()), array('id' => 'desc'));

            return $this->render('MyStreamBundle:Dashboard:index.html.twig', array('user' => $user, 'url' => $url));
        } else {
            header('Location: /');
            exit;
        }
    }

    public function uploadAction(Request $request)
    {

        $video = new video();
        $form = $this->createForm(UploadVideoType::class, $video);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $video->setUser($this->getUser());
            $em = $this->getDoctrine()->getManager();
            $em->persist($video);
            $em->flush();
            return $this->redirectToRoute('my_stream_dashboard');
        }

        return $this->render(
            'MyStreamBundle:Upload:index.html.twig',
            array('form' => $form->createView())
        );
    }

    public function uploadUrlAction (Request $request){
        $video = new video();
        $form = $this->createForm(UploadVideoUrlType::class, $video);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $video->setUser($this->getUser());
            $video->setUpdatedAt(new \DateTime(date('Y-m-d H:i:s')));
            $em = $this->getDoctrine()->getManager();
            $em->persist($video);
            $em->flush();
            return $this->redirectToRoute('my_stream_dashboard');
        }

        return $this->render(
            'MyStreamBundle:UploadUrl:index.html.twig',
            array('form' => $form->createView())
        );
    }

}
