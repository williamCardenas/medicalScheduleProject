<?php

namespace App\Controller;

use App\Entity\Clinic;
use App\Form\ClinicType;
use App\Repository\ClinicRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/clinic")
 */
class ClinicController extends Controller
{
    /**
     * @Route("/", name="clinic_index", methods="GET")
     */
    public function index(ClinicRepository $clinicRepository): Response
    {
        return $this->render('clinic/index.html.twig', ['clinics' => $clinicRepository->findAll()]);
    }

    /**
     * @Route("/new", name="clinic_new", methods="GET|POST")
     */
    public function new(Request $request): Response
    {
        $clinic = new Clinic();
        $form = $this->createForm(ClinicType::class, $clinic);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($clinic);
            $em->flush();

            return $this->redirectToRoute('clinic_index');
        }

        return $this->render('clinic/new.html.twig', [
            'clinic' => $clinic,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="clinic_show", methods="GET")
     */
    public function show(Clinic $clinic): Response
    {
        return $this->render('clinic/show.html.twig', ['clinic' => $clinic]);
    }

    /**
     * @Route("/{id}/edit", name="clinic_edit", methods="GET|POST")
     */
    public function edit(Request $request, Clinic $clinic): Response
    {
        $form = $this->createForm(ClinicType::class, $clinic);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('clinic_edit', ['id' => $clinic->getId()]);
        }

        return $this->render('clinic/edit.html.twig', [
            'clinic' => $clinic,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="clinic_delete", methods="DELETE")
     */
    public function delete(Request $request, Clinic $clinic): Response
    {
        if ($this->isCsrfTokenValid('delete'.$clinic->getId(), $request->request->get('_token'))) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($clinic);
            $em->flush();
        }

        return $this->redirectToRoute('clinic_index');
    }
}
