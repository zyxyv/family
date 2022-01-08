<?php

namespace App\Controller;

use App\Entity\Socialnet;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;

class SocialNetController extends AbstractController
{
    #[Route('/', name: 'social_net')]
    public function index(EntityManagerInterface $em): Response
    {
        if($user = $this->getUser())
        {
            $sn = $user->getSocialnets();
            if(count($sn) <= 0)
            {
                return $this->redirectToRoute('socialnet_base_new');
            } else {
                $socials = $user->getSocialnets();
                if($user->getActive())
                {
                    $social = $em->getRepository(Socialnet::class)->find($user->getActive());
                } else {
                    $social = false;
                }

                return $this->render('social_net/index.html.twig', [
                    'social' => $social,
                    'user' => $user,
                    'socials' => $socials
                ]);
            }
        } else {
            return $this->redirectToRoute('login');
        }
    }

    /**
     * @Route("/getSocial", options={"expose"=true}, name="getSocial")
     */
    public function getSocial(Request $request, EntityManagerInterface $entityManager)
    {
        if($request->isXmlHttpRequest())
        {
            $user = $this->getUser();
            $id = $request->request->get('id');
            $user->setActive($id);
            $entityManager->flush();
            return new JsonResponse(['resp' => 'Todo Ok']);
        } else {
            throw new \Exception('¿Estas tratando de hackearme');
        }
    }
}
