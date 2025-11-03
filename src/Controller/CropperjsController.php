<?php

namespace App\Controller;

use App\Service\UxPackageRepository;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Asset\Packages;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\UX\Cropperjs\Factory\CropperInterface;
use Symfony\UX\Cropperjs\Form\CropperType;

class CropperjsController extends AbstractController
{

    #[Route('/cropperjs', name: 'app_cropperjs')]
       public function index(CropperInterface $cropper, Request $request, Packages $assetsManager): Response
    {
        $path = $assetsManager->getUrl('assets/img/');
       
        $crop = $cropper->createCrop($path.'upncom.png');
        $crop->setCroppedMaxSize(2000, 1500);

        $form = $this->createFormBuilder(['crop' => $crop])
            ->add('crop', CropperType::class, [
                'public_url' => $path.'upncom.png',
                'cropper_options' => [
                    'aspectRatio' => 2000 / 1500,
                ],
            ])
            ->getForm()
        ;

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Get the cropped image data (as a string)
            $crop->getCroppedImage();

            // Create a thumbnail of the cropped image (as a string)
            $crop->getCroppedThumbnail(200, 150);

            // ...
        }

        return $this->render('ux_packages/cropperjs.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}