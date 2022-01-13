<?php

namespace App\Controller;

use App\Entity\Socialnet;
use App\Entity\Invitation;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\Transport;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mailer\MailerInterface;

class InvitationController extends AbstractController
{
    #[Route('/invitation', name: 'invitation')]
    public function index(EntityManagerInterface $em): Response
    {
        if($this->getUser()) {
            $user = $this->getUser();
            $invitations = $em->getRepository(Invitation::class)->findBy(array('email' => $user->getEmail()));
            if($user->getActive())
            {
                $social = $em->getRepository(Socialnet::class)->find($user->getActive());
            } else {
                return $this->redirectToRoute('social_net');
            }
            return $this->render('invitation/index.html.twig', [
                'user' => $user,
                'social' => $social,
                'invitations' => $invitations
            ]);
        } else {
            return $this->redirect('login');
        }
    }

    /**
     * @Route("/passInvitation", options={"expose"=true}, name="passInvitation")
     */
    public function passInvitation(Request $request, EntityManagerInterface $em, MailerInterface $mailer)
    {
        if($request->isXmlHttpRequest())
        {
            $datos = explode(',',$_POST['emails']);
            $social = $em->getRepository(Socialnet::class)->find($_POST['social']);

            foreach($datos as $dato)
            {
                $invitation = new Invitation();
                $invitation->setEmail($dato);
                $invitation->setSocialnet($social);
                $em->persist($invitation);
                $em->flush();
                /*$email = (new Email())
                    ->from('sdiaz@webimnpacto.es')
                    ->to($dato)
                    ->subject('Ha recibido una invitación para unirse a '.$social->getName())
                    ->text('Ha recibido un ainvitación para unirse a la socialnet '.$social->getName().'. Abrase una cuenta con este correo en family y sescubra un nuevo mundo.');

                $mailer->send($email);*/
            }
            return new JsonResponse(['resp' => $datos]);
        } else {
            throw new \Exception('¿Estas tratando de hackearme');
        }
    }
}
