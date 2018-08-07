<?php

namespace App\Controller;

use App\Entity\AgendaConfig;
use App\Form\AgendaConfigType;
use App\Repository\AgendaConfigRepository;
use App\Repository\AgendaRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

/**
 * @Route("/agenda/{agendaId}/medico/{medicoId}/config")
 */
class AgendaConfigController extends Controller
{
    /**
     * @Route("/", name="agenda_config_edit", methods="GET|POST")
     */
    public function edit(Request $request,AgendaConfigRepository $agendaConfigRepository, AgendaRepository $agendaRepository, SessionInterface $session, $agendaId, $medicoId): Response
    {
        $agendaConfig = $agendaConfigRepository->findOneBy(['agenda'=>$agendaId]);
        $agenda = $agendaRepository->findOneBy(['id'=>$agendaId]);
        
        if(empty($agendaConfig)){
            $agendaConfig = new AgendaConfig();
            $agendaConfig->setAgenda($agenda);
        }

        $form = $this->createForm(AgendaConfigType::class, $agendaConfig);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $agenda->setAgendaConfig($agendaConfig);
            $em = $this->getDoctrine()->getManager();
            $em->persist($agendaConfig,$agenda);
            $em->flush();

            $session->getFlashBag()->add('success', 'mensagem.sucesso.editado');
            $session->getFlashBag()->add('_entidade', AgendaConfig::CLASS_NAME );

            return $this->redirectToRoute('agenda_index', ['id' => $agendaId,'medicoId' => $medicoId]);
        }

        return $this->render('agenda_config/edit.html.twig', [
            'agenda_config' => $agendaConfig,
            'form' => $form->createView(),
            'medicoId' => $medicoId,
            'agendaId' => $agendaId
        ]);
    }
}
