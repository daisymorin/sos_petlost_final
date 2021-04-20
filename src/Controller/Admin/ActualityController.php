<?php

namespace App\Controller\Admin;

use App\Entity\Actuality;
use App\Form\ActualityType;
use App\Repository\ActualityRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface;

/**
 * @Route("admin/actuality")
 */
class ActualityController extends AbstractController
{
    /**
     * @Route("/", name="actuality_index", methods={"GET"})
     */
    public function index(Request $request, PaginatorInterface $paginator, ActualityRepository $actualityRepository): Response
    {
        $listActuality = $actualityRepository->findBy([], ['created_at' => 'DESC']);
        $actualities = $paginator->paginate(
            $listActuality, // Requête contenant les données à paginer (ici nos annonces)
            $request->query->getInt('page', 1), // Numéro de la page en cours, passé dans l'URL, 1 si aucune page
            6 // Nombre de résultats par page
        );
        return $this->render('admin/actuality/index.html.twig', [
            'actualities' => $actualities,
        ]);
    }

    /**
     * @Route("/new", name="actuality_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $actuality = new Actuality();
        $form = $this->createForm(ActualityType::class, $actuality);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $actuality->setCreatedAt(New \DateTime());
            $entityManager->persist($actuality);
            $entityManager->flush();

            return $this->redirectToRoute('actuality_index');
        }

        return $this->render('admin/actuality/new.html.twig', [
            'actuality' => $actuality,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="actuality_show", methods={"GET"})
     */
    public function show(Actuality $actuality): Response
    {
        return $this->render('admin/actuality/show.html.twig', [
            'actuality' => $actuality,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="actuality_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Actuality $actuality): Response
    {
        $form = $this->createForm(ActualityType::class, $actuality);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('actuality_index');
        }

        return $this->render('admin/actuality/edit.html.twig', [
            'actuality' => $actuality,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="actuality_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Actuality $actuality): Response
    {
        if ($this->isCsrfTokenValid('delete'.$actuality->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($actuality);
            $entityManager->flush();
        }

        return $this->redirectToRoute('actuality_index');
    }
}
