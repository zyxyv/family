<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Socialnet;
use App\Entity\Post;
use App\Form\PostType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
    /**
     * @Route("/default", options={"expose"=true}, name="default", methods={"GET","POST"})
     * @param Request $request
     * @return Response
     */
    public function index(Request $request, EntityManagerInterface $em): Response
    {
        if($user = $this->getUser())
        {
            if($user->getActive())
            {
                $social = $em->getRepository(Socialnet::class)->find($user->getActive());
            } else {
                return $this->redirectToRoute('social_net');
            }

            $post = new Post();
            $form = $this->createForm(PostType::class, $post);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid())
            {
                $post->setContent($form->get('content')->getData());
                $post->setDate(date('Y-m-d'));
                $post->setMedia(null);
                $post->setParent(null);
                $post->setPostUser($user);
                $post->setSocialnet($social);

                $em->persist($post);
                $em->flush();

                return $this->redirectToRoute('default', [], Response::HTTP_SEE_OTHER);
            }

            return $this->renderForm('default/index.html.twig', [
                'user' => $user,
                'social' => $social,
                'form' => $form,
            ]);
        } else {
            return $this->redirectToRoute('login');
        }

    }
}
