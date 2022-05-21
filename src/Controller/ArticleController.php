<?php
namespace App\Controller;

use App\Entity\Article;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ArticleController extends AbstractController
{
    public function index(): Response
    {
        return $this->render('articles/index.html.twig');
    }

    public function show(ManagerRegistry $doctrine, int $id): Response
    {
        $article = $doctrine->getRepository(Article::class)->find($id);

        return $this->render('articles/show.html.twig', [
            'article' => $article,
        ]);
    }
}
