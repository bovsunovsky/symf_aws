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
}
