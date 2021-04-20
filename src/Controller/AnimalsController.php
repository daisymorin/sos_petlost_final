<?php

namespace App\Controller;

use App\Entity\Animals;
use App\Form\AnimalsType;
use App\Repository\AnimalsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface;


/**
 * @Route("admin/animals")
 */
class AnimalsController extends AbstractController
{
    /**
     * @Route("/", name="animals_index", methods={"GET"})
     */
    public function index(Request $request, PaginatorInterface $paginator, AnimalsRepository $animalsRepository): Response
    {
        $listAnimals = $animalsRepository->findBy([], ['date' => 'DESC']);
        $animals = $paginator->paginate(
            $listAnimals, // Requête contenant les données à paginer (ici nos annonces)
            $request->query->getInt('page', 1), // Numéro de la page en cours, passé dans l'URL, 1 si aucune page
            20 // Nombre de résultats par page
        );
        return $this->render('admin/animals/index.html.twig', [
            'animals' => $animals,
        ]);
    }

    /**
     * @Route("/new", name="animals_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
         // Création de l'instance de "Animals"
        $animal = new Animals();
        $form = $this->createForm(AnimalsType::class, $animal);
        $form->handleRequest($request);

         //Vérifie si le formulaire a été soumis et si les données sont valides
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
             // Hydrate notre annonce avec la date
            $animal->setDate(New \DateTime());
            $entityManager->persist($animal);
            $entityManager->flush();

            return $this->redirectToRoute('animals_index');
        }

        return $this->render('admin/animals/new.html.twig', [
            'animal' => $animal,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="animals_show", methods={"GET"})
     */
    public function show(Animals $animal): Response
    {
        return $this->render('admin/animals/show.html.twig', [
            'animal' => $animal,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="animals_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Animals $animal): Response
    {
        $form = $this->createForm(AnimalsType::class, $animal);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $animal->setDate(New \DateTime());
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('animals_index');
        }

        return $this->render('admin/animals/edit.html.twig', [
            'animal' => $animal,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="animals_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Animals $animal): Response
    {
        if ($this->isCsrfTokenValid('delete'.$animal->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($animal);
            $entityManager->flush();
        }

        return $this->redirectToRoute('animals_index');
    }
}
