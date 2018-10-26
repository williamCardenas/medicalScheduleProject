<?php

namespace App\Controller;

use App\Entity\Agenda;
use App\Entity\Medico;
use App\Entity\AgendaConfig;
use App\Entity\Cliente;
use App\Form\AgendaType;
use App\Repository\AgendaRepository;
use App\Repository\AgendaDataRepository;
use App\Repository\MedicoRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * @Route("/agendamento")
 */
class AgendamentoController extends Controller
{
    /**
     * @Route("/", name="agendamento_index", methods="GET")
     */
    public function index(MedicoRepository $medicoRepository, UserInterface  $user): Response
    {
        $medicos = $medicoRepository->searchResult(['cliente' => $user->getCliente()]);
        return $this->render('agendamento/index.html.twig', ['medicos' => $medicos]);
    }

    /**
     * @Route("/agendas-medicas", name="agendamento_agendas", methods="POST")
     */
    public function agendaMedica(Request $request, MedicoRepository $medicoRepository, AgendaRepository $agendaRepository, UserInterface  $user): JsonResponse
    {
        try{
            $medicos = $medicoRepository->medicosComAgendasArrayResult([
                'cliente'       => $user->getCliente(),
                'dataInicio'    => $request->get('start') ,
                'dataFim'       => $request->get('end')
            ]);
            return new JsonResponse($medicos);
        }catch(\Exception $e){
            return new JsonResponse($e->getMessage(),500);
        }
    }

    /**
     * @Route("/horarios-agenda-medico", name="horarios_agenda_medico", methods="POST")
     */
    public function horarioDisponivelAgenda(Request $request, AgendaRepository $agendaRepository, AgendaDataRepository $agendaDataRepository, UserInterface  $user): JsonResponse
    {
        try{
            $params = [
                'cliente'       => $user->getCliente(),
                'data'          => $request->get('data') ,
                'dataConsulta'  => $request->get('data') ,
                'medico'        => $request->get('medico')
            ];

            $horariosMarcados = $agendaDataRepository->findByParams($params);

            $horarios = $agendaRepository->horariosDisponiveisArray($params, $horariosMarcados);

            return new JsonResponse($horarios);
        }catch(\Exception $e){
            return new JsonResponse($e->getMessage(),500);
        }
    }

}