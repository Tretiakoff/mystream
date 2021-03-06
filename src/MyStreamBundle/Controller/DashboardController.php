<?php

namespace MyStreamBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use EntityBundle\Entity\Video;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use MyStreamBundle\Form\Type\UploadVideoType;
use MyStreamBundle\Form\Type\UploadVideoUrlType;
use MyStreamBundle\Form\Type\EditVideoType;
use Symfony\Component\HttpFoundation\Response;
use Vich\UploaderBundle\Form\Type\VichFileType;

class DashboardController extends Controller
{
    public function indexAction()
    {
        if ($this->container->get('security.context')->isGranted('IS_AUTHENTICATED_FULLY')) {
            $user = $this->get('security.context')->getToken()->getUser();
            $video = $this->getDoctrine()
                ->getRepository('EntityBundle:Video')
                ->findBy(array('authorId'=>$user->getId()), array('id' => 'desc'));
            return $this->render('MyStreamBundle:Dashboard:index.html.twig', array('user'=>$user, 'video'=>$video));
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
        $videoUrl = $video->getVideoName();

        if($form->isSubmitted() && !filter_var($videoUrl, FILTER_VALIDATE_URL) )
        {
            echo ('Veuillez entrer une url');
        }

        if ($form->isSubmitted() && $form->isValid() && filter_var($videoUrl, FILTER_VALIDATE_URL)) {

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

    public function editAction(Request $request, $id)
    {
        if ($this->container->get('security.context')->isGranted('IS_AUTHENTICATED_FULLY')) {
            $video = $this->getDoctrine()
                ->getRepository('EntityBundle:Video')
                ->findOneById(array($id));
            if ($video->getAuthorId() === $this->container->get('security.context')->getToken()->getUser()->getId()) {
                $form = $this->createForm(EditVideoType::class, $video);
                $form->handleRequest($request);
                if ($form->isSubmitted() && $form->isValid()) {
                    $em = $this->getDoctrine()->getManager();
                    $em->persist($video);
                    $em->flush();
                    return $this->redirectToRoute('my_stream_dashboard');
                }
                return $this->render(
                    'MyStreamBundle:Dashboard:edit.html.twig',
                    array('form' => $form->createView(), 'video' => $video)
                );
            } else {
                return $this->redirectToRoute('my_stream_dashboard');
            }
        }
        else {
            return $this->redirectToRoute('my_stream_homepage');
        }

    }

    public function removeAction($id)
    {
        if ($this->container->get('security.context')->isGranted('IS_AUTHENTICATED_FULLY')) {
            $video = $this->getDoctrine()
                ->getRepository('EntityBundle:Video')
                ->findOneById(array($id));
            if ($video->getAuthorId() === $this->container->get('security.context')->getToken()->getUser()->getId()) {
                $em = $this->getDoctrine()->getManager();
                $em->remove($video);
                $em->flush();
            }
            return $this->redirectToRoute('my_stream_dashboard');
        }
    }
}
