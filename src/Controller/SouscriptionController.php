<?php

namespace App\Controller;

use App\Entity\Souscription;
use App\Form\SouscriptionType;
use App\Repository\SouscriptionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/souscription")
 */
class SouscriptionController extends AbstractController
{
    /**
     * @Route("/", name="souscription_index", methods={"GET"})
     */
    public function index(SouscriptionRepository $souscriptionRepository): Response
    {
        return $this->render('souscription/index.html.twig', [
            'souscriptions' => $souscriptionRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="souscription_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $souscription = new Souscription();
        $form = $this->createForm(SouscriptionType::class, $souscription);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($souscription);
            $entityManager->flush();

            return $this->redirectToRoute('souscription_index');
        }

        return $this->render('souscription/new.html.twig', [
            'souscription' => $souscription,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="souscription_show", methods={"GET"})
     */
    public function show(Souscription $souscription): Response
    {
        return $this->render('souscription/show.html.twig', [
            'souscription' => $souscription,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="souscription_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Souscription $souscription): Response
    {
        $form = $this->createForm(SouscriptionType::class, $souscription);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('souscription_index');
        }

        return $this->render('souscription/edit.html.twig', [
            'souscription' => $souscription,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="souscription_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Souscription $souscription): Response
    {
        if ($this->isCsrfTokenValid('delete'.$souscription->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($souscription);
            $entityManager->flush();
        }

        return $this->redirectToRoute('souscription_index');
    }
}
