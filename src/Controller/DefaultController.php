<?php

namespace App\Controller;

use App\Entity\Socialnet;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
    /**
     * @Route("/default", options={"expose"=true}, name="default")
     */
    public function index(EntityManagerInterface $em): Response
    {
        if($user = $this->getUser())
        {
            if($user->getActive())
            {
                $social = $em->getRepository(Socialnet::class)->find($user->getActive());
            } else {
                return $this->redirectToRoute('social_net');
            }

            return $this->render('default/index.html.twig', [
                'user' => $user,
                'social' => $social,
                'controller_name' => 'DefaultController',
            ]);
        } else {
            return $this->redirectToRoute('login');
        }

    }
}
