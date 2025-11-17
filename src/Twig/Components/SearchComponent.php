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

use App\Entity\Article;
use App\Repository\ArticleRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\UX\LiveComponent\Attribute\LiveProp;
use Symfony\UX\LiveComponent\DefaultActionTrait;
use Symfony\UX\LiveComponent\ComponentToolsTrait;
use Symfony\UX\LiveComponent\Attribute\LiveAction;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\TwigComponent\Attribute\ExposeInTemplate;

#[AsLiveComponent]
class SearchComponent{
    use DefaultActionTrait;
    use ComponentToolsTrait;

    private const PER_PAGE = 3;

    #[LiveProp(writable: true, onUpdated: 'onQueryUpdated')]
    public string $query = '';

    #[LiveProp]
    public int $page = 1;

    #[LiveProp]
    public int $magicalId;

    public $articlesArray = [];

    public function __construct(
        private ArticleRepository $articleRepository,
    )
    {
        $this->magicalId =1;
        $this->getArticles();
    }

    #[LiveAction]
    public function more(): void
    {
        ++$this->page;
    }

    #[LiveAction]
    public function reset(): void
    {
        $this->page = 1;
    }

    public function getArticles() : array
    {
        $articles = $this->articleRepository->findBySearchQb($this->query) ;
        
        $articles
            ->setFirstResult(($this->page - 1) * self::PER_PAGE)
            ->setMaxResults(self::PER_PAGE);

        $listArticles = new Paginator($articles, true);
        //$articlesArray = [];

        $this->articlesArray  = array_merge( $this->articlesArray , $listArticles->getQuery()->getResult());
        return $this->articlesArray 
        ;
    }
        public function onQueryUpdated(): void // méthode pour forcer le rendering de la div contenant les articles lors du changement de $this->query
    {
        $this->magicalId++ ;
        $this->page = 1;
    }

    #[ExposeInTemplate('per_page')]
    public function getPerPage(): int
    {
        return self::PER_PAGE;
    }

    #[ExposeInTemplate('hasMore')]
    public function hasMore(): bool
    {
        return \count($this->articlesArray) > ($this->page * self::PER_PAGE);
    }

}
