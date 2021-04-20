<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\AnimalsRepository;
use App\Form\AnimalsType;
use App\Entity\Animals;
use App\Entity\Comment;
use App\Form\CommentType;
use App\Repository\ActualityRepository;
use App\Entity\Actuality;
use Knp\Component\Pager\PaginatorInterface;

class DefaultController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index(AnimalsRepository $animalsRepository): Response
    {
        $listAnimals = $animalsRepository->findBy([], ['date' => 'DESC'], 20);
        return $this->render('front/default/home.html.twig', [
            'animals' => $listAnimals,
        ]);
    }

    /**
     * @Route("/animaux-disparus", name="animaux-disparus")
     */
    public function animalMissing(Request $request, PaginatorInterface $paginator, AnimalsRepository $animalsRepository): Response
    {
        $listAnimals = $animalsRepository->findBy(['missing' => true], ['date' => 'DESC']);
        $animals = $paginator->paginate(
            $listAnimals, // Requête contenant les données à paginer (ici nos annonces)
            $request->query->getInt('page', 1), // Numéro de la page en cours, passé dans l'URL, 1 si aucune page
            20 // Nombre de résultats par page
        );
        return $this->render('front/default/animaux-perdus.html.twig', [
            'animals' => $animals,
        ]);
    }

    /**
     * @Route("/animaux-trouve", name="animaux-trouve")
     */
    public function animalFound(Request $request, PaginatorInterface $paginator, AnimalsRepository $animalsRepository): Response
    {
        $listAnimals = $animalsRepository->findBy(['found' => true], ['date' => 'DESC']);
        $animals = $paginator->paginate(
            $listAnimals, // Requête contenant les données à paginer (ici nos annonces)
            $request->query->getInt('page', 1), // Numéro de la page en cours, passé dans l'URL, 1 si aucune page
            20 // Nombre de résultats par page
        );
        return $this->render('front/default/animaux-trouves.html.twig', [
            'animals' => $animals,
        ]);
    }

    /**
     * @Route("/perdu/{id}", name="showPerdu", methods={"GET", "POST"})
     */
    public function show(Request $request, Animals $animal): Response
    {
       
        return $this->render('front/default/show-perdu.html.twig', [
            'animal' => $animal,
        ]);
    }

    /**
     * @Route("/trouve/{id}", name="showTrouve", methods={"GET", "POST"})
     */
    public function showTrouve(Request $request, Animals $animal): Response
    {
       
        return $this->render('front/default/show-trouve.html.twig', [
            'animal' => $animal,
        ]);
    }

    /**
     * @Route("/actualites", name="actualites")
     */
    public function actualites(Request $request, PaginatorInterface $paginator, ActualityRepository $actualityRepository): Response
    {
        $listActuality = $actualityRepository->findBy([], ['created_at' => 'DESC']);
        $actualities = $paginator->paginate(
            $listActuality, // Requête contenant les données à paginer (ici nos annonces)
            $request->query->getInt('page', 1), // Numéro de la page en cours, passé dans l'URL, 1 si aucune page
            6 // Nombre de résultats par page
        );
        return $this->render('front/default/actualites.html.twig', [
            'actualities' => $actualities,
        ]);
    }

     /**
     * @Route("/actualites/{id}", name="showActualite", methods={"GET"})
     */
    public function showActuality(Actuality $actuality): Response
    {
        return $this->render('front/default/actualite-show.html.twig', [
            'actuality' => $actuality,
        ]);
    }

    /**
     * @Route("/trouve-comment/{id}", name="showCommentTrouve", methods={"GET", "POST"})
     */
    public function showCommentTrouve(Request $request, Animals $animal): Response
    {
        // Création de l'instance de "Comment"
        $comment = new Comment();
        // Création du formulaire en utilisant "CommentType" et on lui passe l'instance
        $form = $this->createForm(CommentType::class, $comment);
        //Récupération des données
        $form->handleRequest($request);

        //Vérifie si le formulaire a été soumis et si les données sont valides
        if ($form->isSubmitted() && $form->isValid()) {
            // Hydrate notre commentaire avec la date
            $comment->setCreatedAt(new \DateTime())
                // Hydrate notre commentaire avec l'article
                ->setAnimals($animal);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($comment);
            // On écrit en base de données
            $entityManager->flush();

            return $this->redirectToRoute('showTrouve', ['id' =>$animal->getId()]);
        }

        return $this->render('front/comment/new-comment-trouve.html.twig', [
            'animal' => $animal,
            'comment' => $comment,
            'form' => $form->createView()
        ]);
    }

     /**
     * @Route("/perdu-comment/{id}", name="showCommentPerdu", methods={"GET", "POST"})
     */
    public function showCommentPerdu(Request $request, Animals $animal): Response
    {
        $comment = new Comment();
        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $comment->setCreatedAt(New \DateTime())
                ->setAnimals($animal);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($comment);
            $entityManager->flush();

            return $this->redirectToRoute('showPerdu', ['id' =>$animal->getId()]);
        }

        return $this->render('front/comment/new-comment-perdu.html.twig', [
            'animal' => $animal,
            'comment' => $comment,
            'form' => $form->createView()
        ]);
    }

    
}
