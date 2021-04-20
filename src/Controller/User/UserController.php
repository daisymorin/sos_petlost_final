<?php

namespace App\Controller\User;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Animals;
use App\Form\AnimalsType;
use App\Repository\AnimalsRepository;

/**
 * @Route("user/profile")
 */


class UserController extends AbstractController
{
    /**
     * @Route("/", name="annonces")
     */
    public function index(AnimalsRepository $animalsRepository): Response
    {
        $listAnimals = $animalsRepository->findBy(['user'=>$this->getUser()]);
        return $this->render('user/annonce/annonce-user.html.twig', [
            'animals' => $listAnimals,
        ]);
    }

     /**
     * @Route("/new-annonce", name="new_annonce", methods={"GET","POST"})
     */
    public function newAnnonce(Request $request): Response
    {
         // Création de l'instance de "Animals"
        $animal = new Animals();
         // Création du formulaire en utilisant "AnimalsType" et on lui passe l'instance
        $form = $this->createForm(AnimalsType::class, $animal);
        //Récupération des données
        $form->handleRequest($request);

         //Vérifie si le formulaire a été soumis et si les données sont valides
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
             // Hydrate notre annonce avec la date
            $animal->setDate(New \DateTime());
             //On enregistre les données
            $entityManager->persist($animal);
             // On écrit en base de données
            $entityManager->flush();

            return $this->redirectToRoute('annonces');
        }

        return $this->render('user/annonce/new.html.twig', [
            'animal' => $animal,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/annonce/{id}", name="show-annonce", methods={"GET"})
     */
    public function showAnnonceUser(Animals $animal): Response
    {
        return $this->render('user/annonce/show-user.html.twig', [
            'animal' => $animal,
        ]);
    }

    /**
     * @Route("/annonce/{id}/edit", name="edit-annonce", methods={"GET","POST"})
     */
    public function editAnnonceUser(Request $request, Animals $animal): Response
    {
        $form = $this->createForm(AnimalsType::class, $animal);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $animal->setDate(New \DateTime());
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('annonces');
        }

        return $this->render('user/annonce/edit.html.twig', [
            'animal' => $animal,
            'form' => $form->createView(),
        ]);
    }
}
