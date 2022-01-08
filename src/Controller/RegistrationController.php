<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class RegistrationController extends AbstractController
{
    #[Route('/register', name: 'register')]
    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager): Response
    {
        $error = '';
        $out = 0;
        $format = array('jpg', 'jpeg', 'png', 'gif', 'webp');
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);
        $file = $form->get('avatar')->getData();

        if($file)
        {
            $name = $_POST['registration_form']['email'].'.png';
            if(in_array($file->guessExtension(), $format) && $_FILES['registration_form']['size']['avatar'] <= 1000000) {
                $file->move('img/avatars',$_POST['registration_form']['email'].'.png');
                $out = 1;
            } else {
                $error = 'Error subiendo la imagen';
                $out = -1;
            }
        }
        if ($form->isSubmitted() && $form->isValid() && $out >= 0) {
            // encode the plain password
            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );
            $user->setRoles((array)'ROLE_USER');
            $user->setAvatar($name);
            $entityManager->persist($user);
            $entityManager->flush();
            // do anything else you need here, like send an email
            $this->getUser();
            return $this->redirectToRoute('default');
        }

        return $this->render('registration/register.html.twig', [
            'error' => $error,
            'user' => $user,
            'registrationForm' => $form->createView(),
        ]);
    }
}
