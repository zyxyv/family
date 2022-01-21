<?php

namespace App\Controller;

use App\Entity\Socialnet;
use App\Form\SocialnetType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

require_once 'EditionFile.php';

#[Route('/socialnet/base')]
class SocialnetBaseController extends AbstractController
{
    #[Route('/new', name: 'socialnet_base_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $socialnet = new Socialnet();
        $form = $this->createForm(SocialnetType::class, $socialnet);
        $form->handleRequest($request);
        $user = $this->getUser();
        if($user->getActive()) {
            $social = $entityManager->getRepository(Socialnet::class)->find($user->getActive());
        } else {
            $social = false;
        }

        if ($form->isSubmitted() && $form->isValid()) {
            $format = array('jpg', 'jpeg', 'png', 'gif', 'webp');
            EditionFile::editDbl($_FILES['socialnet'],$format,$_POST['socialnet']['name'],'img/socialnet/',null,'.png',1000000, 'image');
            $socialnet->addUser($this->getUser());
            $socialnet->setImage($_POST['socialnet']['name'].'.png');
            $entityManager->persist($socialnet);
            $entityManager->flush();

            return $this->redirectToRoute('social_net');
        }

        return $this->renderForm('socialnet_base/new.html.twig', [
            'user' => $user,
            'socialnet' => $socialnet,
            'social' => $social,
            'form' => $form,
        ]);
    }

    #[Route('/{id}/edit', name: 'socialnet_base_edit', methods: ['GET', 'POST'])]
    public function edit(Socialnet $socialnet, EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();

        if($_POST)
        {
            if($_FILES && $_FILES['image']['error'] == 0 && $_POST['name'] != '')
            {
                $format = array('jpg', 'jpeg', 'png', 'gif', 'webp');
                $flag = EditionFile::edit($_FILES['image'],$format,$_POST['name'],'img/socialnet/',$_POST['old'],'.png',1000000);
            } else {
                EditionFile::rename_file($_POST['old'],$_POST['name'],'img/socialnet/','.png');
            }
            $socialnet->setName($_POST['name']);
            $entityManager->flush();
        }

        $social = $entityManager->getRepository(Socialnet::class)->find($user->getActive());

        return $this->renderForm('socialnet_base/edit.html.twig', [
            'user' => $user,
            'socialnet' => $socialnet,
            'social' => $social
        ]);
    }

    #[Route('/{id}', name: 'socialnet_base_delete', methods: ['POST'])]
    public function delete(Socialnet $socialnet, EntityManagerInterface $entityManager): Response
    {
       $user = $this->getUser();

       $socialnet->removeUser($user);

       $user->setActive(null);
       $entityManager->flush();

        $query = "SELECT count(user_id) as count from socialnet_user WHERE socialnet_id = ".$socialnet->getId();
        $rs = $entityManager->getConnection()->fetchAllAssociative($query);

        if($rs[0]['count'] == 0)
        {
            if(file_exists('img/socialnet/'.$socialnet->getName().'.png'))
            {
                unlink('img/socialnet/'.$socialnet->getName().'.png');
            }
            $entityManager->remove($socialnet);
            $entityManager->flush();
        }

        return $this->redirectToRoute('social_net', [], Response::HTTP_SEE_OTHER);
    }
}

