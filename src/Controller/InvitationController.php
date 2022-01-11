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

class InvitationController extends AbstractController
{
    #[Route('/invitation', name: 'invitation')]
    public function index(EntityManagerInterface $em): Response
    {
        if($this->getUser()) {
            $user = $this->getUser();
            if($user->getActive())
            {
                $social = $em->getRepository(Socialnet::class)->find($user->getActive());
            } else {
                return $this->redirectToRoute('social_net');
            }
            return $this->render('invitation/index.html.twig', [
                'user' => $user,
                'social' => $social
            ]);
        } else {
            return $this->redirect('login');
        }
    }

    /**
     * @Route("/passInvitation", options={"expose"=true}, name="passInvitation")
     */
    public function getSocial(Request $request, EntityManagerInterface $em)
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
                $email = (new Email())
                    ->setFrom('sdiaz@webimnpacto.es')
                    ->setTo($dato)
                    ->setSubject('Ha recibido una invitación para unirse a '.$social->getName())
                    ->setTextBody('Ha recibido un ainvitación para unirse a la socialnet '.$social->getName().'. Abrase una cuenta con este correo en family y sescubra un nuevo mundo.');

                $transport = Transport::fromDsn('smtp://localhost');
                $transport->send($email);
            }



            return new JsonResponse(['resp' => $datos]);
        } else {
            throw new \Exception('¿Estas tratando de hackearme');
        }
    }
}
