<?php

namespace App\Controller;

use App\Entity\Album;
use App\Entity\Socialnet;
use App\Form\AlbumType;
use App\Repository\AlbumRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/album/base')]
class AlbumBaseController extends AbstractController
{
    #[Route('/new', name: 'album_base_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        if($user = $this->getUser())
        {
            $social = $entityManager->getRepository(Socialnet::class)->find($user->getActive());
        } else {
            return $this->redirect('login');
        }
        $album = new Album();
        $form = $this->createForm(AlbumType::class, $album);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $format = array('jpg', 'jpeg', 'png', 'gif', 'webp');
            EditionFile::editDbl($_FILES['album'],$format,$_POST['album']['name'],'img/albums/',null,'.png',1000000, 'image');
            $user = $this->getUser();
            $social = $entityManager->getRepository(Socialnet::class)->find($user->getActive());
            $album->setSocialnet($social);
            $album->setOwner($user);
            $album->setImage($_POST['album']['name'].'.png');
            $entityManager->persist($album);
            $entityManager->flush();

            return $this->redirectToRoute('album', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('album_base/new.html.twig', [
            'user' => $user,
            'social' => $social,
            'album' => $album,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'album_base_show', methods: ['GET'])]
    public function show(Album $album): Response
    {
        return $this->render('album_base/show.html.twig', [
            'album' => $album,
        ]);
    }

    #[Route('/{id}/edit', name: 'album_base_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Album $album, EntityManagerInterface $entityManager): Response
    {
        if($user = $this->getUser())
        {
            $social = $entityManager->getRepository(Socialnet::class)->find($user->getActive());
        } else {
            return $this->redirect('login');
        }
        if($_POST)
        {
            if($_FILES && $_FILES['image']['error'] == 0 && $_POST['name'])
            {
                $format = array('jpg', 'jpeg', 'png', 'gif', 'webp');
                $flag = EditionFile::edit($_FILES['image'],$format,$_POST['name'],'img/albums/',$_POST['old'],'.png',1000000);
            } else {
                EditionFile::rename_file($_POST['old'],$_POST['name'],'img/albums/','.png');
            }
            $album->setDescription($_POST['description']);
            $album->setName($_POST['name']);
            $entityManager->flush();
        }

        return $this->renderForm('album_base/edit.html.twig', [
            'album' => $album,
            'user' => $user,
            'social' => $social
        ]);
    }

    #[Route('/{id}', name: 'album_base_delete', methods: ['POST'])]
    public function delete(Request $request, Album $album, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$album->getId(), $request->request->get('_token'))) {
            $entityManager->remove($album);
            $entityManager->flush();
        }

        return $this->redirectToRoute('album', [], Response::HTTP_SEE_OTHER);
    }
}
