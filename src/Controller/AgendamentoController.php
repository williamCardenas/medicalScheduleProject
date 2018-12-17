<?php

namespace App\Controller;

use App\Entity\AgendaData;
use App\Repository\AgendaRepository;
use App\Repository\AgendaDataRepository;
use App\Repository\MedicoRepository;
use App\Service\AgendaService;
use App\Form\AgendamentoType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\HttpFoundation\JsonResponse;

use DateTime;

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
        $agendaData = new AgendaData();
        $form = $this->createForm(AgendamentoType::class, $agendaData,['user'=>$user]);

        $medicos = $medicoRepository->searchResult(['cliente' => $user->getCliente()]);
        return $this->render('agendamento/index.html.twig', [
            'medicos' => $medicos, 
            'form' => $form->createView(),
        ]);
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
                'medico' => ['operator'=>'=', 'value'=> $request->get('medicoId')],
            ];

            $horariosMarcados = $agendaDataRepository->findByParams($params);
            $agendas = $agendaRepository->findByParams($params);
            
            $agendaService = new AgendaService();
            $horarios = $agendaService->horariosDisponiveisArray($agendas, $horariosMarcados);

            return new JsonResponse($horarios);
        }catch(\Exception $e){
            return new JsonResponse($e->getMessage(),500);
        }
    }

    /**
     * @Route("/new", name="agendamento_new", methods="POST")
     */
    public function new(Request $request, AgendaRepository $agendaRepository, UserInterface  $user): JsonResponse
    {
        try{
            
            $agendaData = new AgendaData();
            $form = $this->createForm(AgendamentoType::class, $agendaData, ['user'=>$user]);
            $form->handleRequest($request);
            
            $params = [
                'data' => $agendaData->getDataConsulta()->format('Y-m-d'),
                'hora' => $agendaData->getDataConsulta()->format('H:i:s'),
                'medico' => ['operator'=>'=', 'value'=> $form->get('medico')->getData()],
            ];
            $agendas = $agendaRepository->findByParams($params);

            if(count($agendas) > 1){
                throw new \Exception('horario para mais de uma agenda');
            }

            $agendaData->setAgenda($agendas[0]);
            $agendaData->setDataAtualizacao(new DateTime());
            $agendaData->setUsuarioAtualizacaoId($user);

            if ($form->isValid()) {

                $em = $this->getDoctrine()->getManager();
                $em->persist($agendaData);
                $em->flush();

                return new JsonResponse(array('message' => 'Success'), 200);
            }else {
                return new JsonResponse(array('message' => 'Invalid'), 200);
            }
        }catch(\Exception $e){
            return new JsonResponse($e->getMessage(),500);
        }
    }
}