<?php

namespace App\Controller;

use App\Entity\Agenda;
use App\Entity\AgendaConfig;
use App\Form\AgendaType;
use App\Repository\AgendaRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

/**
 * @Route("/agenda/medico/{medicoId}")
 */
class AgendaController extends Controller
{
    /**
     * @Route("/", name="agenda_index", methods="GET")
     */
    public function index(AgendaRepository $agendaRepository, SessionInterface $session, $medicoId): Response
    {
        $agenda = $agendaRepository->findBy(['medico'=>$medicoId],['dataFimAtendimento'=>'DESC']);

        return $this->render('agenda/index.html.twig', ['agendas' => $agenda, 'medicoId' => $medicoId]);
    }

    /**
     * @Route("/new", name="agenda_new", methods="GET|POST")
     */
    public function new(Request $request, SessionInterface $session, $medicoId): Response
    {
        $agenda = new Agenda();
        $form = $this->createForm(AgendaType::class, $agenda,array('user' => $this->getUser()));
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $agendaConfig = new AgendaConfig();
            $agendaConfig->setAgenda($agenda);
            
            $agenda->setAgendaConfig($agendaConfig);

            $em = $this->getDoctrine()->getManager();
            $em->persist($agendaConfig,$agenda);
            $em->flush();

            $session->getFlashBag()->add('success', 'mensagem.sucesso.novo');
            $session->getFlashBag()->add('_entidade', Agenda::CLASS_NAME );

            return $this->redirectToRoute('agenda_index', ['medicoId' => $medicoId]);
        }

        return $this->render('agenda/new.html.twig', [
            'agenda' => $agenda,
            'form' => $form->createView(),
            'medicoId' => $medicoId
        ]);
    }

    /**
     * @Route("/view/{id}", name="agenda_show", methods="GET")
     */
    public function show(Agenda $agenda, $medicoId): Response
    {
        return $this->render('agenda/show.html.twig', ['agenda' => $agenda, 'medicoId' => $medicoId]);
    }

    /**
     * @Route("/edit/{id}", name="agenda_edit", methods="GET|POST")
     */
    public function edit(Request $request, Agenda $agenda, SessionInterface $session, $medicoId): Response
    {
        $form = $this->createForm(AgendaType::class, $agenda);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            $session->getFlashBag()->add('success', 'mensagem.sucesso.editado');
            $session->getFlashBag()->add('_entidade', Agenda::CLASS_NAME );

            return $this->redirectToRoute('agenda_index', ['medicoId' => $medicoId]);
        }

        return $this->render('agenda/edit.html.twig', [
            'agenda' => $agenda,
            'form' => $form->createView(),
            'medicoId' => $medicoId
        ]);
    }

    /**
     * @Route("/{id}", name="agenda_delete", methods="DELETE")
     */
    public function delete(Request $request, Agenda $agenda, SessionInterface $session, $medicoId): Response
    {
        if ($this->isCsrfTokenValid('delete'.$agenda->getId(), $request->request->get('_token'))) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($agenda);
            $em->flush();

            $session->getFlashBag()->add('success', 'mensagem.sucesso.deletar');
            $session->getFlashBag()->add('_entidade', Agenda::CLASS_NAME );
        }

        return $this->redirectToRoute('agenda_index', ['medicoId' => $medicoId]);
    }
}
