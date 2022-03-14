<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\Comment;
use App\Form\CommentType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CommentController extends AbstractController
{
    #[Route('/comment', name: 'app_comment')]
    public function index(): Response
    {
        return $this->render('comment/index.html.twig', [
            'controller_name' => 'CommentController',
        ]);
    }

    #[Route('/comment/remove/{id}', name: 'comment_remove')]
    public function remove(Comment $comment, EntityManagerInterface $entityManager) {

        $entityManager->remove($comment);
        $entityManager->flush();


        return $this->redirectToRoute('article_id', ['id' => $comment->getArticle()->getId()]);
    }

    #[Route('comment/edit/{id}', name: 'comment_edit')]
    public function edit(Comment $comment, Request $request, EntityManagerInterface $entityManager)
    {
        $form = $this->createForm(CommentType::class, $comment);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($comment);
            $entityManager->flush();

            return $this->redirectToRoute('app_user');
        }

        return $this->render('comment/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
