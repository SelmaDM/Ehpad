<?php

namespace App\Controller;

use App\Entity\Offer;
use App\Entity\Souscription;

use App\Form\OfferType;
use App\Repository\OfferRepository;
use App\Repository\UserRepository;
use App\Repository\SouscriptionRepository;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/offer")
 */
class OfferController extends AbstractController
{
      /**
     * @Route("/", name="offer_index", methods={"GET"})
     */
   
    public function indexOffre(OfferRepository $offerRepository): Response
    {
        return $this->render('offer/index.html.twig', [
            'offers' => $offerRepository->findAll(),
        ]);
    }
    
   
    
    /**
     * @Route("/{id}", name="offer_show", methods={"GET"})
     */
    public function show(Offer $offer): Response
    {
        return $this->render('offer/show.html.twig', [
            'offer' => $offer,
        ]);
    }

   
/**
     * @Route("/{id}/subscribe_to_offer", name="offer_subscribe", methods={"GET","POST"})
     */
    public function subscribeToOffer (Request $request, Offer $offer,UserRepository $userRepository, SouscriptionRepository $souscriptionRepository,OfferRepository $offerRepository): Response
    {   
        // Utilisateur non connecté Deny Acess
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $user1 = $this->getUser();  
        $user =$user1->getSouscriptions();

        $souscription = new Souscription($user1, $offer);  
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($souscription);
        $entityManager->flush();
      
        return $this->render('offer/index.html.twig', [
            'offers' => $offerRepository->findAll(),
        ]);
      
    }
        

    





}
