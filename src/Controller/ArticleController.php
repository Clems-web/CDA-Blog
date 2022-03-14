<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\Comment;
use App\Entity\User;
use App\Form\ArticleType;
use App\Form\CommentType;
use App\Repository\ArticleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class ArticleController extends AbstractController
{
    #[Route('/', name: 'app_article')]
    public function index(ArticleRepository $articleRepository): Response
    {
        $articles = $articleRepository->findAll();
        return $this->render('article/index.html.twig', [
            'articles' => $articles,
        ]);
    }

    #[Route('/article/add', name: 'add_article')]
    #[IsGranted('ROLE_AUTHOR')]
    public function add(Request $request, EntityManagerInterface $entityManager): Response
    {
        $article = new Article();
        $form = $this->createForm(ArticleType::class, $article);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid() ) {


            $file = $form['thumbnail']->getData();
            $ext = $file->guessExtension();
            if (!$ext) {
                $ext = 'bin';
            }

            $fileName = $file->getClientOriginalName().uniqid();
            $file->move('thumbnail', $fileName);
            $article->setThumbnail( $fileName);

            $article->setAuthor($this->getUser());
            $article->setPublishedDate(new \DateTime());

            $entityManager->persist($article);
            $entityManager->flush();

           return $this->redirectToRoute('app_article');
        }

        return $this->render('article/add.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[Route('/article/{id}', name: 'article_id')]
    public function showArticleById(Article $article,Request $request, EntityManagerInterface $entityManager)
    {
        $comment = new Comment();
        $form = $this->createForm(CommentType::class, $comment);


        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $comment->setAuthor($this->getUser());
            $comment->setArticle($article);
            $comment->setDate(new \DateTime());

            $entityManager->persist($comment);
            $entityManager->flush();

            return $this->redirectToRoute("article_id", ["id" => $article->getId()]);

        }

        return $this->render('article/articleById.html.twig', [
            'article' => $article,
            'form' => $form->createView(),
        ]);
    }

    #[IsGranted('ROLE_AUTHOR')]
    #[Route('/article/edit/{id}', name: 'article_edit')]
    public function edit(Article $article, Request $request, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ArticleType::class, $article);
        $oldImg = $article->getThumbnail();
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {


            $imageFile = $form['thumbnail']->getData();

            if (!empty($imageFile)) {

                $fileName = $imageFile->getClientOriginalName().uniqid();
                $imageFile->move('thumbnail', $fileName);
                $article->setThumbnail($fileName);

            }

            else {
                $article->setThumbnail($oldImg);
            }

            $entityManager->persist($article);
            $entityManager->flush();
            return $this->redirectToRoute('app_user');
        }

        return $this->render('article/add.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[IsGranted('ROLE_AUTHOR')]
    #[Route('article/delete/{id}', name: 'article_delete')]
    public function del(Article $article, EntityManagerInterface $entityManager) {

        $entityManager->remove($article);
        $entityManager->flush();


        return $this->redirectToRoute('app_user');
    }
}
