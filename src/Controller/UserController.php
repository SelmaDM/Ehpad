<?php

namespace App\Controller;

use App\Entity\User;

use App\Form\UserType;
use App\Form\UserAdminType;
use App\Form\UserType1Type;
use App\Form\SouscriptionType;

use App\Repository\UserRepository;
use App\Repository\SouscriptionRepository;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/mon-espace-client")
 */
class UserController extends AbstractController
{
       /**
 * @Route("", name="user_home", methods={"GET","POST"})
 */
public function index(UserRepository $userRepository, Request $request): Response
{
    // Récupérer l'utilisateur connecté
    $user = $this->getUser(); 

    // Créer un formulaire lié à ce utilisateur
    $form = $this->createForm(UserType1Type::class, $user);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        $this->getDoctrine()->getManager()->flush();

    }

    return $this->render('espace-client/index.html.twig', [
        'form' => $form->createView()
    ]);
}
    
    
    /**
     * @Route("/new", name="user_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute('user_index');
        }

        return $this->render('user/new.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }

   
  /**
     * @Route("/mes-souscriptions", name="user_souscriptions", methods={"GET"})
     */
    public function souscription(UserRepository $userRepository, SouscriptionRepository $souscriptionRepository): Response
    {
        $user = $this->getUser();
        
        return $this->render('souscription/show.html.twig', [
            'souscription' => $user->getSouscriptions() ,

        ]) ;  
    }


/**
     * @Route("/{id}", name="user_show", methods={"GET"})
     */
    public function show(User $user, UserRepository $userRepository): Response
    {
        return $this->render('user/show.html.twig', [
            'user' => $user,
            'users' => $userRepository->findAll(),
        ]);
    }

    /**
     * @Route("/{id}/edit", name="user_edit", methods={"GET","POST"})
     */
    public function user_edit(Request $request, User $user): Response
    {
        $form = $this->createForm(UserAdminType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('user_home');
        }

        return $this->render('user/edit.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }
/**
     * @Route("/{id}", name="user_delete", methods={"DELETE"})
     */
    public function delete(Request $request, User $user): Response
    {
        if ($this->isCsrfTokenValid('delete'.$souscription->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($souscription);
            $entityManager->flush();
        }

        return $this->redirectToRoute('user_index');
    }
 


}
