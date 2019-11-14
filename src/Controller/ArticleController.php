<?php

namespace App\Controller;

use App\Entity\Article;
use App\Form\ArticleType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ArticleController extends AbstractController
{
    /**
     * @Route("/article", name="article")
     */
    public function index()
    {
        $em = $this->getDoctrine()->getManager();
        $articles = $em->getRepository(Article::class)->findAll();
        return $this->render('article/index.html.twig', [
            'controller_name' => 'ArticleController',
            'articles'=>$articles
        ]);
    }

    /**
     * @route("/article/single/{article}", name="single_article")
     */
    public function single(Article $article)
    {
        return $this->render('article/single.html.twig', [
            'article' => $article,
        ]) ;
    }

    /**
     * @param Request $request
     * @Route("/article/create", name="create_article")
     */
    public function create(Request $request)
    {
        // Созаём екземпляр обьекта сущности
        $article = new Article();

        // Создаём форму (передаём в неё форму (описание сущности) и обьект сущности)
        $form = $this->createForm(ArticleType::class, $article);

        // Обрабатываем полученный реквест полученный после нажатия сабмит
        $form->handleRequest($request);

        // Проверяем полученные данные
        if ($form->isSubmitted() && $form->isValid()){

            // Получаем из формы обьект с данными
            $article = $form->getData();

            // Через сетер дописываем данные ( время создания )
            $article->setCreatedAt(new \DateTime('now'));

            //Получаем менеджер сущностей
            $em = $this->getDoctrine()->getManager();

            // Подготавливаем данныем обьекта для записи в БД
            $em->persist($article);

            // Записываем в БД
            $em->flush();

            return $this->redirectToRoute('article');
        }
        return $this->render('article/form.html.twig', [
            //передавая форму создаём вид
            'form' => $form->createView(),
        ]);

    }

    /**
     * @param Request $request
     * @param Article $article
     * @Route("/article/update/{article}", name="update_article")
     */
    public function update(Request $request, Article $article)
    {
        $form = $this->createForm(ArticleType::class, $article,[
            'action' => $this->generateUrl('update_article',[
                'article'=> $article->getId()
            ]),
            'method'=>'POST',
        ]);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $article = $form->getData();
            $article->setUpdatedAt(new \DateTime('now'));

            $em = $this->getDoctrine()->getManager();
            $em->flush();

            return $this->redirectToRoute("article");
        }

        return $this->render('article/form.html.twig',[
            'form' => $form->createView()
        ]);
    }

    /**
     * @param Article $article
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     * @Route("/article/delete/{article}", name="article_delete")
     */
    public function delete(Article $article)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($article);
        $em->flush();

        return $this->redirectToRoute("article");
    }
}
