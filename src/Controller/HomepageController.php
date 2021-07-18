<?php

namespace App\Controller;


use App\Form\HomepageType;
use App\Repository\HomepageRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class HomepageController extends AbstractController
{
    /**
     * @Route("/", name="homepage_index", methods={"GET"})
     */
    public function index(HomepageRepository $homepageRepository): Response
    {
        return $this->render('homepage/index.html.twig', [
            'homepages' => $homepageRepository->findAll(),
        ]);
    }

    

   /**
     * @Route("/", name="homepage_show", methods={"GET"})
     */
    public function showhomepage(Homepage $homepage): Response
    {
        return $this->render('homepage/show.html.twig', [
            'homepage' => $homepage,
        ]);
    }

 
}
