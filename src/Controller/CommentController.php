<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\Comment;
use App\Form\CommentType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class CommentController extends AbstractController
{
    /**
     * @Route("/comments/create/{article}", name="comment_create_form")
     */
    public function create(Request $request, Article $article)
    {
        $comment = new Comment();
        $form = $this->createForm(CommentType::class, $comment,[
            //назначение екшена , для правильного роутинга , т.е. для того чтобы мы попали на
            // создание именно комментария как связанной сущности
            'action'=>$this->generateUrl('comment_create_form', [
                'article'=> $article->getId()
            ]),
            'method'=>'POST'
        ]);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $comment = $form->getData();
            $comment->setCreatedAt(new \DateTime('now'));
            $comment->setUpdatedAt(new \DateTime('now'));
            // указываем к какой именно статье относится коментарий
            $comment->setArticle($article);

            $em = $this->getDoctrine()->getManager();
            $em->persist($comment);
            $em->flush();

            return $this->redirectToRoute("single_article", ['article'=>$article->getId()]);
        }

        return $this->render('comment/form.html.twig',[
            'form'=>$form->createView(),
            'article'=>$article
        ]);
    }

    /**
     * @param Request $request
     * @param Article $article
     * @param Comment $comment
     * @Route("/comment/update/{article}/{comment}", name="comment_update_form")
     */
    public function update(Request $request, Article $article, Comment $comment)
    {
        $form = $this->createForm(CommentType::class, $comment,[
           'action' => $this->generateUrl('comment_update_form',[
               'article'=>$article->getId(),
               'comment'=>$comment->getId()
           ]) ,
            'method'=>'POST',
        ]);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $comment= $form->getData();
            $comment->setUpdatedAt(new \DateTime('now'));

            $em = $this->getDoctrine()->getManager();
            $em->flush();

            return $this->redirectToRoute('single_article',['article'=>$article->getId()]);
        }

        return $this->render('comment/form.html.twig',[
            'form'=>$form->createView(),
            'article'=>$article
        ]);
    }

    /**
     * @param Article $article
     * @param Comment $comment
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     * @Route("/comment/delete/{article}/{comment}", name="comment_delete")
     */
    public function delete(Article $article, Comment $comment)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($comment);
        $em->flush();

        return $this->redirectToRoute('single_article', ['article'=>$article->getId()]);
    }
}
