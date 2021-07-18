<?php

namespace App\Controller;

use App\Entity\User;

use App\Form\UserType;
use App\Form\UserType1Type;
use App\Entity\Souscription;
use App\Form\SouscriptionType;
use App\Repository\SouscriptionRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/mon-espace-agent")
 */
class AgentController extends AbstractController
{
       /**
     * @Route("", name="agent_public_index", methods={"GET"})
     */
    public function index(UserRepository $userRepository, Request $request): Response
    {
        // Récupérer l'utilisateur connecté
        $user = $this->getUser(); 
        return $this->render('espace-agent/index.html.twig');
    }

       /**
     * @Route("/souscription", name="agent_public_souscription_index", methods={"GET"})
     */
    public function index_souscription(SouscriptionRepository $souscriptionRepository, Request $request): Response
  
    {
        return $this->render('souscription/index.html.twig', [
            'souscriptions' => $souscriptionRepository->findAll(),
        ]);
    }

        /**
     * @Route("/souscription/{id}/edit", name="souscription_edit1", methods={"GET","POST"})
     */
    public function edit_souscription(Request $request, Souscription $souscription): Response
    {
        $form = $this->createForm(SouscriptionType::class, $souscription);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('agent_public_souscription_index');
        }
      
       
        return $this->render('souscription/edit.html.twig', [
        'souscription' => $souscription,
        'form' => $form->createView(),
        ]);
    }

    }
