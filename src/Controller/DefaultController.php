<?php


namespace App\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
    /**
     * @return Response
     * @Route("/")
     */
    public function index()
    {
       return new Response('Hello !!');
    }

    /**
     * @return Response
     * @Route("/simp/{name}")
     */
    public function simple($name)
    {
//        return new Response('All simple !!'.$name);
        return $this->render('default/index.html.twig',[
            'name'=>$name,
        ]);
    }
}