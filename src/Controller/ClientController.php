<?php

namespace App\Controller;

use App\Entity\Client;
use App\Form\ClientType;
use App\Repository\ClientRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\DateTime;

class ClientController extends AbstractController
{
    /**
     * @Route("/client", name="client")
     */
    public function index()
    {
        $em = $this->getDoctrine()->getManager();

        $clients = $em->getRepository(Client::class)->findAll();

        return $this->render('client/index.html.twig', [
            'controller_name' => 'ClientController',
            'clients' => $clients
        ]);
    }

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @Route("/client/create", name="create_client")
     */
    public function create(Request $request)
    {
        $client = new Client();

        $form= $this->createForm(ClientType::class,$client);

        $form->handleRequest($request);

        if($form->isSubmitted()){

            $data = $form->getData();

            $data->setCreatedAt(new \DateTime('now'));

            if($form->isValid()){
                $em = $this->getDoctrine()->getManager();
                $em->persist($data);
                $em->flush();

                return $this->redirectToRoute("client");
            }

        }

        return $this->render('client/form.html.twig',[
            'form'=>$form->createView(),
        ]);
    }

    /**
     * @param Request $request
     * @param Client $client
     * @Route("/client/update/{client}" , name="update_client")
     */
    public function update(Request $request, Client $client)
    {
        //создаём форму ***Type, передаём в неё обьект $***, и массив доп. настроек,
        // так как изначально форма работает на create то для работы как update требуется передать action и Id
        $form = $this->createForm(ClientType::class, $client ,[
            'action'=>$this->generateUrl("update_client",[
                'client'=> $client->getId()
            ]),
            'method'=>'POST'
        ]);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){

            $em = $this->getDoctrine()->getManager();
            $em->flush();

            return $this->redirectToRoute("client");
        }

        return $this->render('client/form.html.twig',[
            'form'=>$form->createView()
        ]);
    }

    /**
     * @param Client $client
     * @Route("/client/delete/{client}", name="delete_client")
     */
    public function delete(Client $client)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($client);
        $em->flush();

        return $this->redirectToRoute("client");
    }

    /**
     * @param Client $client
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/client/profile/{client}", name="client_profile")
     */
    public function profile(Client $client)
    {
        return $this->render('client/profile.html.twig',[
            'client' => $client
        ]);

    }
}
