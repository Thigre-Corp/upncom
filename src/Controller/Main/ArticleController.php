<?php

namespace App\Controller\Main;

use App\Entity\Article;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/blog')]
final class ArticleController extends AbstractController
{
    #[Route(name: 'app_article_index')]
    public function index(): Response
    {
        return $this->render('article/index.html.twig');
    }

    #[Route('/{id}', name: 'app_article_show', methods: ['GET'])]
    public function show(Article $article): Response
    {
        return $this->render('article/show.html.twig', [
            'article' => $article,
        ]);
    }

}
