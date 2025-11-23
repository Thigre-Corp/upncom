<?php

namespace App\Controller\Main;

use App\Entity\Realisation;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/realisation')]
final class RealisationController extends AbstractController
{
    #[Route(name: 'app_realisation', methods: ['GET'])]
    public function index(): Response
    {
        return $this->render('realisation/index.html.twig', [
        ]);
    }

    #[Route('/{id}', name: 'app_realisation_show', methods: ['GET'])]
    public function show(Realisation $realisation): Response
    {
        return $this->render('realisation/show.html.twig', [
            'article' => $realisation,
        ]);
    }

}
