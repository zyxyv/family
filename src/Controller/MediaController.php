<?php

namespace App\Controller;

use App\Entity\Album;
use App\Entity\Photo;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MediaController extends AbstractController
{
    /**
     * @Route("/media/{id}", options={"expose"=true}, name="media")
     */
    public function index(Album $album, EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();

        $soported = array("image/pjpeg","image/jpeg","image/png","image/gif","image/webp","video/x-msvideo","video/mpeg","video/ogg","video/webm","video/3gpp");
        if (in_array($_FILES["file"]["type"],$soported) && $_FILES["file"]["size"] <= 5000000)
        {
            if (move_uploaded_file($_FILES["file"]["tmp_name"], "img/pictures/".$_FILES['file']['name'])) {
                $photo = new Photo();
                $photo->setOwnerPhoto($user);
                $photo->setAlbum($album);
                $photo->setMediaPhoto($_FILES['file']['name']);
                $entityManager->persist($photo);
                $entityManager->flush();
                $id = $photo->getId();
                return new JsonResponse(['resp' => "/family2.1/public/img/pictures/".$_FILES['file']['name'],
                    'sop' => '', 'id' => $id]);
            } else {
                throw new \Exception('¿Estas tratando de hackearme');
            }
        } else {
            return new JsonResponse(['sop' => 'Solo archivos de imagenes y videos que no superen los 5MB',
                'resp' => '']);
        }
    }

    /**
     * @Route("/mediaDel/{id}", options={"expose"=true}, name="mediaDel")
     */
    public function deletePhoto(Request $request, Photo $photo, EntityManagerInterface $entityManager): Response
    {

            if ($request->isXmlHttpRequest()) {
               $entityManager->remove($photo);
               $entityManager->flush();
                return new JsonResponse(['resp' => $photo]);
            } else {
                throw new \Exception('¿Estas tratando de hackearme');
            }
    }
}
