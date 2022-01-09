<?php

namespace App\Controller;

use App\Entity\Socialnet;
use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

require_once "EditionFile.php";

#[Route('/user')]
class UserController extends AbstractController
{
    #[Route('/', name: 'user_index', methods: ['GET'])]
    public function index(UserRepository $userRepository): Response
    {
        return $this->render('user/index.html.twig', [
            'users' => $userRepository->findAll(),
        ]);
    }

    #[Route('/{id}/edit', name: 'user_edit', methods: ['GET', 'POST', 'FILES'])]
    public function edit(Request $request, User $user, EntityManagerInterface $entityManager): Response
    {
        $format = array('jpg', 'jpeg', 'png', 'gif', 'webp');

        if(isset($_FILES['avatar']) && $_FILES['avatar']['error'] == 0)
        {
            $flag = EditionFile::edit($_FILES['avatar'],$format,$_POST['email'],'img/avatars/',$_POST['old'],'.png', 1000000);
        }

        if(isset($_POST['email']))
        {
            $data = array(
                'firstname' => $_POST['firstname'],
                'lastname' => $_POST['lastname'],
                'email' => $_POST['email'],
                'birthname' => $_POST['birthname'],
                'avatar' => $flag,
                'id' => $user->getId()
            );
            $user->setFirstname($data['firstname']);
            $user->setLastname($data['lastname']);
            $user->setEmail($data['email']);
            $user->setBirthname($data['birthname']);
            $user->setAvatar($data['avatar']);
            $entityManager->persist($user);
            $entityManager->flush();
            $this->redirectToRoute('default');
        }

        $social = $entityManager->getRepository(Socialnet::class)->find($user->getActive());

        return $this->renderForm('user/edit.html.twig', [
            'social' => $social,
            'user' => $user,
        ]);
    }

    #[Route('/{id}', name: 'user_delete', methods: ['POST'])]
    public function delete(Request $request, User $user, EntityManagerInterface $entityManager): Response
    {
        $img = $user->getEmail().'.png';
        if(file_exists('img/avatars/'.$img))
        {
            unlink('img/avatars/'.$img);
        }
        if ($this->isCsrfTokenValid('delete'.$user->getId(), $request->request->get('_token'))) {
            $entityManager->remove($user);
            $entityManager->flush();
        }

        return $this->redirectToRoute('login', [], Response::HTTP_SEE_OTHER);
    }
}
