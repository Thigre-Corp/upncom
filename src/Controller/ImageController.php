<?php

namespace App\Controller;

use App\Entity\Image;
use App\Form\ImageType;
use App\Service\ImageService;
use App\Repository\ImageRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

final class ImageController extends AbstractController
{

    #[Route('/image', name: 'app_image', methods: ['GET', 'POST'])]
    public function new(
        Request $request,
        ImageService $imageService,
        ): Response
    {
        $image = new Image();
        $form = $this->createForm(ImageType::class, $image);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            
            $dataFiles = $form->get('files')->getData();
            foreach ($dataFiles as $dataFile) {
                $images[] = $imageService->standardizator($dataFile, 'service', 1000);
            }
            return $this->redirectToRoute('admin');
        }

        return $this->render('image/new.html.twig', [
            'image' => $image,
            'form' => $form,
        ]);
    }
}
