<?php

namespace App\Controller;

use App\Entity\Album;
use App\Entity\Socialnet;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AlbumController extends AbstractController
{
    #[Route('/album', name: 'album')]
    public function index(EntityManagerInterface $em): Response
    {

        if($user = $this->getUser())
        {
            $social = $em->getRepository(Socialnet::class)->find($user->getActive());
            $albums = $social->getAlbums();
        } else {
            return $this->redirect('login');
        }

        return $this->render('album/index.html.twig', [
            'social' => $social,
            'user' => $user,
            'albums' => $albums
        ]);
    }
}
