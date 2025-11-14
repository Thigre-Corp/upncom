<?php

namespace App\Controller\Main;

use App\Entity\Article;
use App\Form\SearchType;
use App\Model\SearchData;
use App\Repository\TagRepository;
use App\Repository\ArticleRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/blog')]
final class ArticleController extends AbstractController
{
    #[Route(name: 'app_article_index', methods: ['GET'])]
    public function index(ArticleRepository $articleRepository, TagRepository $tagRepository, PaginatorInterface $paginator, Request $request): Response
    {
        // if(!isNull(tag.id) ){
        //     dd(tag.id);
        // }
        $searchData = new SearchData();
        $form = $this->createForm(SearchType::class, $searchData);
        
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $searchData->page = $request->query->getInt('page', 1);
            $q=$searchData->q;
        }
        $q ?? $q= '';

        $pagination = $paginator->paginate(
            $articleRepository->findBySearch($q), /* query NOT result */
            $request->query->getInt('page', 1), /* page number */
            6 /* limit per page */
        );


        return $this->render('article/index.html.twig', [
            // 'articles' => $articleRepository->findAll(),
            'pagination' => $pagination,
            'tags'=>$tagRepository->findAll(),
            // 'searchForm' => $form->createView(),

        ]);
    }

    #[Route('/{id}', name: 'app_article_show', methods: ['GET'])]
    public function show(Article $article): Response
    {
        return $this->render('article/show.html.twig', [
            'article' => $article,
        ]);
    }

}
