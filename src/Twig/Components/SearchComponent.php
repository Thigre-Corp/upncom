<?php
/*
    Twig Component to manage images
    permet de:
        - proposer une image par défaut en cas d'absence d'objet image
        - met en place les balises et textes alternatifs si le fichier est de type webp ou svg
        - met une croix rouge si le fichier ne correspond pas à ces deux possibilités 
            ->(fichiers autres que webp et svg ne peuvent pas sortir de ImageUploadService...)
*/

namespace App\Twig\Components;


use App\Repository\ArticleRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Symfony\Component\ObjectMapper\ObjectMapperInterface;
use Symfony\UX\LiveComponent\Attribute\LiveProp;
use Symfony\UX\LiveComponent\DefaultActionTrait;
use Symfony\UX\LiveComponent\ComponentToolsTrait;
use Symfony\UX\LiveComponent\Attribute\LiveAction;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\TwigComponent\Attribute\ExposeInTemplate;

#[AsLiveComponent]
class SearchComponent
{
    use DefaultActionTrait;
    use ComponentToolsTrait;

    private const PER_PAGE = 3;

    #[LiveProp(writable: true, onUpdated: 'onQueryUpdated')]
    public string $query = '';

    #[LiveProp]
    public int $page = 1;

    public array $articlesArray = [];

    private int $nbArticles = 0;

    public function __construct(
        private ArticleRepository $articleRepository,
        private readonly ObjectMapperInterface $objectMapper,
    ) {
        $articles = $this->articleRepository
            ->findBySearchQb($this->query)
            ->setFirstResult(0)
            ->setMaxResults(self::PER_PAGE);

        $listArticles = new Paginator($articles, true);
        $this->nbArticles = $listArticles->count();
        $this->articlesArray  = $listArticles->getQuery()->getResult();
    }

    #[LiveAction]
    public function more(): void
    {
        ++$this->page;
    }

    public function getArticles(): array
    {
        if ($this->page > 1) {
            $articles = $this->articleRepository->findBySearchQb($this->query);

            $articles
                ->setFirstResult(($this->page - 1) * self::PER_PAGE)
                ->setMaxResults(self::PER_PAGE);

            $this->articlesArray  = array_merge($this->articlesArray, $articles->getQuery()->getResult());
        }
        return $this->articlesArray;
    }

    public function onQueryUpdated(): void // méthode pour forcer le rendering de la div contenant les articles lors du changement de $this->query
    {
        $this->page = 1;

        $articles = $this->articleRepository
            ->findBySearchQb($this->query)
            ->setFirstResult(0)
            ->setMaxResults(self::PER_PAGE);

        $listArticles = new Paginator($articles, true);
        $this->nbArticles = $listArticles->count();
        // dd($this->nbArticles);
        $this->articlesArray  = $listArticles->getQuery()->getResult();
    }

    #[ExposeInTemplate('per_page')]
    public function getPerPage(): int
    {
        return self::PER_PAGE;
    }

    // #[ExposeInTemplate('hasMore')]
    public function hasMore(): bool
    {
        dump('nbArticles=' . $this->nbArticles);
        dump('nbPages=' . $this->page * self::PER_PAGE);
        return (($this->nbArticles) > ($this->page * self::PER_PAGE));
    }
}
