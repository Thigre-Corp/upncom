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


use App\Repository\RealisationRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Symfony\UX\LiveComponent\Attribute\LiveProp;
use Symfony\UX\LiveComponent\DefaultActionTrait;
use Symfony\UX\LiveComponent\ComponentToolsTrait;
use Symfony\UX\LiveComponent\Attribute\LiveAction;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\TwigComponent\Attribute\ExposeInTemplate;

#[AsLiveComponent]
class RealisationSearchComponent
{
    use DefaultActionTrait;
    use ComponentToolsTrait;

    private const PER_PAGE = 6;

    #[LiveProp(writable: true, onUpdated: 'onQueryUpdated')]
    public string $query = '';

    #[LiveProp]
    public int $page = 1;

    public array $realisationsArray = [];

    private int $nbRealisations = 0;

    public function __construct(
        private RealisationRepository $realisationRepository,
    ) {
        $realisations = $this->realisationRepository
            ->findBySearchQb($this->query)
            ->setFirstResult(0)
            ->setMaxResults(self::PER_PAGE);

        $listRealisations = new Paginator($realisations, true);
        $this->nbRealisations = $listRealisations->count();
        $this->realisationsArray  = $listRealisations->getQuery()->getResult();
    }

    #[LiveAction]
    public function more(): void
    {
        ++$this->page;
    }

    public function getRealisations(): array
    {
        if ($this->page > 1) {
            $realisations = $this->realisationRepository->findBySearchQb($this->query);

            $realisations
                ->setFirstResult(($this->page - 1) * self::PER_PAGE)
                ->setMaxResults(self::PER_PAGE);

            $this->realisationsArray  = array_merge($this->realisationsArray, $realisations->getQuery()->getResult());
        }
        return $this->realisationsArray;
    }

    public function onQueryUpdated(): void // méthode pour forcer le rendering de la div contenant les realisations lors du changement de $this->query
    {
        $this->page = 1;

        $realisations = $this->realisationRepository
            ->findBySearchQb($this->query)
            ->setFirstResult(0)
            ->setMaxResults(self::PER_PAGE);

        $listRealisations = new Paginator($realisations, true);
        $this->nbRealisations = $listRealisations->count();
        // dd($this->nbRealisations);
        $this->realisationsArray  = $listRealisations->getQuery()->getResult();
    }

    #[ExposeInTemplate('per_page')]
    public function getPerPage(): int
    {
        return self::PER_PAGE;
    }

    // #[ExposeInTemplate('hasMore')]
    public function hasMore(): bool
    {
        dump('nbRealisations=' . $this->nbRealisations);
        dump('nbPages=' . $this->page * self::PER_PAGE);
        return (($this->nbRealisations) > ($this->page * self::PER_PAGE));
    }

}
