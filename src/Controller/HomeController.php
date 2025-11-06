<?php

namespace App\Controller;

use App\Entity\Image;
use App\Entity\Contact;
use App\Form\ImageType;
use App\Form\ContactType;
use App\Service\ImageService;
use Symfony\Component\Mime\Email;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

final class HomeController extends AbstractController
{
    #[Route('/', name: 'home')]
    public function index(): Response
    {
        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }

    #[Route('/image', name: 'app_image')]
    public function image(Request $request, ImageService $imageService ): Response
    {
        $image = new Image() ;
        $form = $this->createForm(ImageType::class, $image);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            
            $dataFiles = $form->get('files')->getData();
            foreach ($dataFiles as $dataFile) {
                $imageService->standardizator($dataFile, 'service', 1000);
            }
        return $this->redirectToRoute('home');
        
        }


        return $this->render('image/index.html.twig', [
            'form' => $form,
        ]);
    }
}
