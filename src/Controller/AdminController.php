<?php

namespace App\Controller;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Article;
use App\Entity\Offer;
use App\Form\OfferType;
use App\Repository\OfferRepository;
use App\Entity\User;

use App\Form\ArticleType;
use App\Entity\Homepage;
use App\Form\HomepageType;
use App\Form\UserAdminType;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\HttpFoundation\File\File;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Repository\ContactRepository;
use App\Repository\ArticleRepository;
use App\Repository\HomepageRepository;
use App\Repository\UserRepository;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminController extends AbstractController
{
    /**
     * @Route("/admin", name="admin")
     */
    public function index()
    {
        return $this->render('admin/index.html.twig', [
            'controller_name' => 'AdminController',
        ]);
    }

    /**
     * @Route("/admin/contact", name="admin_index")
     */
     
      public function indexContact(ContactRepository $contactRepository): Response
    {
        return $this->render('contact/index.html.twig', [
            'contacts' => $contactRepository->findAll(),
        ]);
    }

    /**
     * @Route("/admin/offre", name="offer_index1", methods={"GET"})
     */
   
    public function indexOffre(OfferRepository $offerRepository): Response
    {
        return $this->render('offer/index1.html.twig', [
            'offers' => $offerRepository->findAll(),
        ]);
    }

    /**
     * @Route("/admin/offre/new", name="offer_new", methods={"GET","POST"})
     */
    public function new_offer(Request $request): Response
    {
        $offer = new Offer();
        $form = $this->createForm(OfferType::class, $offer);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($offer);
            $entityManager->flush();

            return $this->redirectToRoute('offer_index1');
        }

        return $this->render('offer/new.html.twig', [
            'offer' => $offer,
            'form' => $form->createView(),
        ]);
    }


    /**
     * @Route("/admin/offre/{id}/delete", name="offre_delete1", methods={"DELETE"})
     */
    public function delete_offre(Request $request, Offer $offer): Response
    {
        if ($this->isCsrfTokenValid('delete'.$offer->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($offer);
            $entityManager->flush();
        }

        return $this->redirectToRoute('offer_index1');
    }
    /**
     * @Route("/admin/offre/{id}/edit", name="offer_edit1", methods={"GET","POST"})
     */
    public function edit_offer(Request $request, Offer $offer): Response
    {
        $form = $this->createForm(OfferType::class, $offer);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();
            return $this->redirectToRoute('offer_index1');
        }

        return $this->render('offer/edit.html.twig', [
            'offer' => $offer,
            'form' => $form->createView(),
        ]);
    }
    
    

    
       /**
     * @Route("/admin/article", name="admin_index1")
     */
     
      public function indexarticle(articleRepository $articleRepository): Response
    {
        return $this->render('article/index1.html.twig', [
            'articles' => $articleRepository->findAll(),
        ]);
    }
    
   
       /**
     * @Route("/admin/article/new", name="article_new", methods={"GET","POST"})
     */
      public function new(Request $request, SluggerInterface $slugger)
        {
            $article = new Article();
            $form = $this->createForm( ArticleType::class, $article);
            $form->handleRequest($request);
    
            if ($form->isSubmitted() && $form->isValid()) {
                /** @var UploadedFile $brochureFile */
                $brochureFile = $form->get('brochure')->getData();
    
                // this condition is needed because the 'brochure' field is not required
                // so the PDF file must be processed only when a file is uploaded
                if ($brochureFile) {
                    $originalFilename = pathinfo($brochureFile->getClientOriginalName(), PATHINFO_FILENAME);
                    // this is needed to safely include the file name as part of the URL
                    $safeFilename = $slugger->slug($originalFilename);
                    $newFilename = $safeFilename.'-'.uniqid().'.'.$brochureFile->guessExtension();
    
                    // Move the file to the directory where brochures are stored
                    try {
                        $brochureFile->move(
                            $this->getParameter('article_img_directory'),
                            $newFilename
                        );
                    } catch (FileException $e) {
                        // ... handle exception if something happens during file upload
                    }
    
                    // updates the 'brochureFilename' property to store the PDF file name
                    // instead of its contents
                    $article->setBrochureFilename($newFilename);
                }
    
                // ... persist the $product variable or any other work
                $entityManager = $this->getDoctrine()->getManager();
                    
                $entityManager->persist($article);
                $entityManager->flush();


                return $this->redirectToRoute('article_index');
                }
    

             return $this->render('article/new.html.twig', [
            'form' => $form->createView(),
        ]);
       }
    
    
      /**
     * @Route("admin/article/{id}/edit", name="article_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Article $article): Response
    {
        $imgname = $article ->getBrochureFilename();
        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('article_index');
        }
        else {
             $article->setBrochureFilename(
            new File($this->getParameter('article_img_directory').'/'.$article->getBrochureFilename())
        );
       
        return $this->render('article/edit.html.twig', [
        'article' => $article,
        'form' => $form->createView(),
        'imgname'=> $imgname
        ]);
    }}

    /**
     * @Route("admin/article/{id}/delete", name="article_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Article $article): Response
    {
        if ($this->isCsrfTokenValid('delete'.$article->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($article);
            $entityManager->flush();
        }

        return $this->redirectToRoute('article_index');
    }
    
    
        /**
     * @Route("/admin/homepage", name="admin_index2")
     */
     
      public function indexhomepage(homepageRepository $homepageRepository): Response
    {
        return $this->render('homepage/index2.html.twig', [
            'homepages' => $homepageRepository->findAll(),
        ]);
    }
    
    
    /**
     * @Route("admin/homepage/new", name="homepage_new", methods={"GET","POST"})
     */
    public function newhomepage(Request $request): Response
    {
        $homepage = new Homepage();
        $form = $this->createForm(HomepageType::class, $homepage);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($homepage);
            $entityManager->flush();

            return $this->redirectToRoute('homepage_index');
        }

        return $this->render('homepage/new.html.twig', [
            'homepage' => $homepage,
            'form' => $form->createView(),
        ]);
    }
    
    
    
       /**
     * @Route("admin/homepage/{id}/edit", name="homepage_edit", methods={"GET","POST"})
     */
    public function edithomepage(Request $request, Homepage $homepage): Response
    {
        $form = $this->createForm(HomepageType::class, $homepage);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('homepage_index');
        }

        return $this->render('homepage/edit.html.twig', [
            'homepage' => $homepage,
            'form' => $form->createView(),
        ]);
    }

 
       /**
     * @Route("admin/user", name="user_index", methods={"GET"})
     */
    public function index_user(UserRepository $userRepository): Response
    {
        return $this->render('user/index.html.twig', [
            'users' => $userRepository->findAll(),
        ]);
    }
 /**
     * @Route("admin/user/{id}/edit", name="user_edit1", methods={"GET","POST"})
     */
    public function user_edit(Request $request, User $user): Response
    {
        $form = $this->createForm(UserAdminType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('user_index');
        }

        return $this->render('user/edit.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }
       
}



