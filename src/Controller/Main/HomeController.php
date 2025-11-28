<?php

namespace App\Controller\Main;

use App\Entity\Client;
use App\Entity\Article;
use App\Entity\Service;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

final class HomeController extends AbstractController
{
    #[Route('/', name: 'home')]
    public function index(EntityManagerInterface $em): Response
    {
        $services = $em->getRepository(Service::class)->findAll();
        $articles = $em->getRepository(Article::class)->findLastArticles(4);
        $clients = $em->getRepository(Client::class)->findAll();
        return $this->render('home/index.html.twig', [
            // 'controller_name' => 'HomeController',
            'services' => $services,
            'articles' => $articles,
            'clients' => $clients,
        ]);
    }

    #[Route('/privacy', name: 'rgpd')]
    public function rgpd(): Response
    {
        return $this->render('home/privacy.html.twig', [
            'meta' => 'Politique de Confidentialité relative au données collectées - RGPD',
        ]);
    }

}
