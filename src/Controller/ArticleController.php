<?php
namespace App\Controller;

use App\Entity\Article;
// use App\Entity\Comment;
// use App\Form\ArticleFormType;
// use App\Form\CommentFormType;
// use Doctrine\Persistence\ManagerRegistry;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\FormField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
// use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
// use Symfony\Component\HttpFoundation\Response;
// use Symfony\Component\Validator\Validator\ValidatorInterface;

use Psr\Log\LoggerInterface;

class ArticleController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Article::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('userId')->hideOnForm(),
            'title',
            'text',
            // DateTimeField::new('updatedAt')->hideOnIndex()->hideOnForm(),
        ];
    }
    // public function show(ManagerRegistry $doctrine, int $id): Response
    // {
        // $entityManager = $doctrine->getManager();
        // $article = $entityManager->getRepository(Article::class)->find($id);

        // if (!$article) {
            // throw $this->createNotFoundException(
                // 'No article found for id '.$id
            // );
        // }

        // $comment = new Comment();
        // $form    = $this->createForm(CommentFormType::class, $comment);
        // return $this->render('article/show.html.twig', [
            // 'article'      => $article,
            // 'comment_form' => $form->createView(),
        // ]);
    // }

/*
 *     public function edit(ManagerRegistry $doctrine, int $id): Response
 *     {
 *         $entityManager = $doctrine->getManager();
 *         $article = $entityManager->getRepository(Article::class)->find($id);
 * 
 *         if (!$article) {
 *             throw $this->createNotFoundException(
 *                 'No article found for id '.$id
 *             );
 *         }
 * 
 *         $form = $this->createForm(ArticleFormType::class, $article);
 *         $logger->info($id);
 *         // if ($form->isSubmitted() && $form->isValid()) {
 *             // $article = $form->getData();
 *             // return $this->redirectToRoute('task_success');
 *         // }
 * 
 *         return $this->renderForm('article/edit.html.twig', [
 *             'form' => $form,
 *         ]);
 *     }
 */
}
