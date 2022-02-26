<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Socialnet;
use App\Entity\Post;
use App\Form\PostType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

class DefaultController extends AbstractController
{
    /**
     * @Route("/default", options={"expose"=true}, name="default", methods={"GET","POST"})
     * @param Request $request
     * @return Response
     */
    public function index(Request $request, EntityManagerInterface $em, SluggerInterface $slugger): Response
    {
        if($user = $this->getUser())
        {
            $msg = array('type' => null, 'text' => false);
            $newFilename = null;
            if($user->getActive())
            {
                $social = $em->getRepository(Socialnet::class)->find($user->getActive());
            } else {
                return $this->redirectToRoute('social_net');
            }

            $post = new Post();
            $form = $this->createForm(PostType::class, $post);
            $form->handleRequest($request);

            if($_POST || $_FILES)
            {
                if ($form->isSubmitted() && $form->isValid() && $form->get('content')->getData() !== '<br>')
                {
                    $file = $form->get('media')->getData();
                    if($file)
                    {
                        $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                        $safeFilename = $slugger->slug($originalFilename);
                        $newFilename = $safeFilename.'-'.uniqid().'.'.$file->guessExtension();
                        try {
                            $file->move(
                                'img/pictures',
                                $newFilename
                            );
                        } catch (FileException $e) {
                            echo "Error subiendo el archivo";
                        }
                    }
                    $post->setContent($form->get('content')->getData());
                    $post->setDate(date('Y-m-d'));
                    $post->setMedia($newFilename);
                    $post->setParent(null);
                    $post->setPostUser($user);
                    $post->setSocialnet($social);

                    $em->persist($post);
                    $em->flush();

                    return $this->redirectToRoute('default', [], Response::HTTP_SEE_OTHER);
                } else {
                    $msg['type'] = 'danger';
                    $msg['text'] = 'Hay algún error en el formulario.';
                }
            }


            return $this->renderForm('default/index.html.twig', [
                'user' => $user,
                'social' => $social,
                'form' => $form,
                'msg' => $msg,
            ]);
        } else {
            return $this->redirectToRoute('login');
        }

    }
}
