<?php

namespace App\Controller;

use App\Entity\Agenda;
use App\Form\AgendaType;
use App\Repository\AgendaRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/agenda/medico/{medicoId}")
 */
class AgendaController extends Controller
{
    /**
     * @Route("/", name="agenda_index", methods="GET")
     */
    public function index(AgendaRepository $agendaRepository, $medicoId): Response
    {
        $agenda = $agendaRepository->findBy(['medico'=>$medicoId]);

        return $this->render('agenda/index.html.twig', ['agendas' => $agenda, 'medicoId' => $medicoId]);
    }

    /**
     * @Route("/new", name="agenda_new", methods="GET|POST")
     */
    public function new(Request $request): Response
    {
        $agenda = new Agenda();
        $form = $this->createForm(AgendaType::class, $agenda);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($agenda);
            $em->flush();

            return $this->redirectToRoute('agenda_index');
        }

        return $this->render('agenda/new.html.twig', [
            'agenda' => $agenda,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="agenda_show", methods="GET")
     */
    public function show(Agenda $agenda): Response
    {
        return $this->render('agenda/show.html.twig', ['agenda' => $agenda]);
    }

    /**
     * @Route("/{id}/edit", name="agenda_edit", methods="GET|POST")
     */
    public function edit(Request $request, Agenda $agenda): Response
    {
        $form = $this->createForm(AgendaType::class, $agenda);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('agenda_edit', ['id' => $agenda->getId()]);
        }

        return $this->render('agenda/edit.html.twig', [
            'agenda' => $agenda,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="agenda_delete", methods="DELETE")
     */
    public function delete(Request $request, Agenda $agenda): Response
    {
        if ($this->isCsrfTokenValid('delete'.$agenda->getId(), $request->request->get('_token'))) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($agenda);
            $em->flush();
        }

        return $this->redirectToRoute('agenda_index');
    }
}
