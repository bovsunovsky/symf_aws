<?php


namespace App\Controller;
// Подключаем сущность Notes;
use App\Entity\Notes;
// Подключаем форму NotesType;
use App\Form\NotesType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
    /**
     * @Route("/", name="index")
     */
    // передаём в екшен Request $request
    public function index(Request $request)
    {
        // получаем менеджер сущности (Entity Manager)
        $em = $this->getDoctrine()->getManager();

        // создаём новую сущность
        $note = new Notes();
        // создаём форму и передаём туда сущность
        $form = $this->createForm(NotesType::class, $note);
        $form->handleRequest($request);

        // если форма засабмичена и данные валидны
        if ($form->isSubmitted() && $form->isValid()){
            // вытягиваем данные из формы
            $data = $form->getData();
            // готовим данные для записи в базу
            $em->persist($note);
            //записываем в базу
            $em->flush();

            // чтобы после успешной отправки значения не оставались в форме делаем редирект
            return $this->redirectToRoute("index");
        }

        // получим данные для отображения на фронте
        $notes = $em->getRepository(Notes::class)->findAll();

        return $this->render('default/index.html.twig',[
            'controller_name'=>'DefaultController',
            'form'=>$form->createView(),
            'notes'=> $notes
        ]);
    }

    /**
     * @Route("/remove/{note}", name="remove_note")
     */
    public function removeNote(Notes $note, Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $em->remove($note);
        $em->flush();

        return $this->redirectToRoute('index');
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