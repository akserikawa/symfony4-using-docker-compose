<?php

namespace App\Controller;

use App\Entity\Bicycle;
use App\Form\BicycleType;
use App\Repository\BicycleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/bicycle")
 */
class BicycleController extends AbstractController
{
    /**
     * @Route("/", name="bicycle_index", methods={"GET"})
     */
    public function index(BicycleRepository $bicycleRepository): Response
    {
        return $this->render('bicycle/index.html.twig', [
            'bicycles' => $bicycleRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="bicycle_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $bicycle = new Bicycle();
        $form = $this->createForm(BicycleType::class, $bicycle);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($bicycle);
            $entityManager->flush();

            return $this->redirectToRoute('bicycle_index');
        }

        return $this->render('bicycle/new.html.twig', [
            'bicycle' => $bicycle,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="bicycle_show", methods={"GET"})
     */
    public function show(Bicycle $bicycle): Response
    {
        return $this->render('bicycle/show.html.twig', [
            'bicycle' => $bicycle,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="bicycle_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Bicycle $bicycle): Response
    {
        $form = $this->createForm(BicycleType::class, $bicycle);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('bicycle_index', [
                'id' => $bicycle->getId(),
            ]);
        }

        return $this->render('bicycle/edit.html.twig', [
            'bicycle' => $bicycle,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="bicycle_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Bicycle $bicycle): Response
    {
        if ($this->isCsrfTokenValid('delete'.$bicycle->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($bicycle);
            $entityManager->flush();
        }

        return $this->redirectToRoute('bicycle_index');
    }
}
