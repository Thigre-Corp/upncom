<?php

namespace App\Twig\Components;

use App\Entity\Article;
use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent]
final class ArticleComponent
{
    public Article $article;
    public string $class;
    public string $id;

    public function getArticle(): Article
    {
        return $this->article;
    }
    public function getClass(): string
    {
        return $this->class;
    }
    public function getId():string
    {
        return $this->id;
    }
}
