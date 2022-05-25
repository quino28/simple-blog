<?php
namespace App\Controller;

use App\Entity\Article;
use App\Entity\Comment;
use App\Form\CommentFormType;
use App\Repository\CommentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Context\AdminContext;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\FormField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;

class ArticleController extends AbstractCrudController
{
    private $twig;
    private $entityManager;

    public function __construct(AdminUrlGenerator $adminUrlGenerator, EntityManagerInterface $entityManager, Security $security)
    {
        $this->adminUrlGenerator = $adminUrlGenerator;
        $this->entityManager     = $entityManager;
        $this->security          = $security;
    }

    public static function getEntityFqcn(): string
    {
        return Article::class;
    }

    public function configureFields(string $pageName): iterable
    {
        yield IdField::new('userId')
            ->onlyOnDetail();
        yield AssociationField::new('user')
            ->hideOnForm();
        yield TextField::new('title');
        yield TextField::new('text');
        yield DateTimeField::new('createdAt')
            ->onlyOnDetail();
        yield DateTimeField::new('updatedAt')
            ->onlyOnDetail();
    }

    public function configureActions(Actions $actions): Actions
    {
        $viewComment = Action::new('Comment', null)
            ->linkToCrudAction('showComment');

        return $actions
            ->add(Crud::PAGE_INDEX, Action::DETAIL)
            ->add(Crud::PAGE_INDEX, $viewComment)
            ->reorder(Crud::PAGE_INDEX, [Action::DETAIL, Action::EDIT, 'Comment']);
    }

    public function createEntity(string $entityFqcn)
    {
        $article = new Article();
        $user    = $this->security->getUser();

        $article->setUser($user);
        $article->setUserId($user->getId());

        return $article;
    }

    public function showComment(Request $request, CommentRepository $commentRepository, AdminContext $context, ManagerRegistry $doctrine)
    {
        $article       = $context->getEntity()->getInstance();
        $entityManager = $doctrine->getManager();
        $comments      = $entityManager->getRepository(Comment::class)->findBy([
            'article_id' => $article->getId(),
        ]);

        $comment = new Comment();
        $form    = $this->createForm(CommentFormType::class, $comment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $comment->setArticle($article);
            $this->entityManager->persist($comment);
            $this->entityManager->flush();

            $url = $this->adminUrlGenerator
                ->setController(ArticleController::class)
                ->setAction('showComment')
                ->setEntityId($article->getId())
                ->generateUrl();
            return $this->redirect($url);
        }

        return $this->render('comment/show.html.twig', [
            'article'      => $article,
            'comments'     => $comments,
            'comment_form' => $form->createView(),
        ]);
    }

    public function deleteComment(ManagerRegistry $doctrine, Request $request)
    {
        $comment = $doctrine->getRepository(Comment::class)->find($request->query->get('id'));
        $article = $comment->getArticle();

        $entityManager = $doctrine->getManager();
        $entityManager->remove($comment);
        $entityManager->flush();

        $url = $this->adminUrlGenerator
            ->setController(ArticleController::class)
            ->setAction('showComment')
            ->setEntityId($article->getId())
            ->generateUrl();
        return new RedirectResponse($url);
    }
}
