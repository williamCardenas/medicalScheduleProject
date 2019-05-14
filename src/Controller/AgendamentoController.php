<?php

namespace App\Controller;

use App\Entity\AgendaData;
use App\Entity\Agenda;
use App\Repository\AgendaRepository;
use App\Repository\AgendaDataRepository;
use App\Repository\AgendaDataStatusRepository;
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
use Symfony\Component\Translation\TranslatorInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

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
     * @Route("/agenda", name="agendamento_agenda", methods="GET")
     */
    public function agenda(MedicoRepository $medicoRepository, UserInterface  $user): Response
    {
        $agendaData = new AgendaData();
        $form = $this->createForm(AgendamentoType::class, $agendaData,['user'=>$user]);

        $medicos = $medicoRepository->searchResult(['cliente' => $user->getCliente()]);
        return $this->render('agendamento/agenda.html.twig', [
            'medicos' => $medicos, 
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/agendas-medicas", name="agendamento_agendas", methods="POST")
     */
    public function agendaMedica(Request $request, MedicoRepository $medicoRepository, AgendaDataRepository $agendaDataRepository, UserInterface  $user): JsonResponse
    {
        try{
            $qb = $medicoRepository->medicosComAgendas([
                'cliente'       => $user->getCliente(),
                'dataInicio'    => $request->get('start') ,
                'dataFim'       => $request->get('end')
            ]);

            $qb->select("M, A");
            $medicos = $qb->getQuery()->getResult();

            $resultMedicos = [];
            foreach($medicos as $medico){
                $medicoArr['id'] = $medico->getId();
                $medicoArr['corAgenda'] = $medico->getCorAgenda();
                $medicoArr['nome'] = $medico->getNome();
                $coutAgenda = 0;
                foreach($medico->getAgenda() as $agenda){
                    $medicoArr['agenda'][$coutAgenda]['id'] = $agenda->getId();
                    $medicoArr['agenda'][$coutAgenda]['dataInicioAtendimento'] = $agenda->getDataInicioAtendimento();
                    $medicoArr['agenda'][$coutAgenda]['dataFimAtendimento'] = $agenda->getDataFimAtendimento();
                    $medicoArr['agenda'][$coutAgenda]['fimDeSemana'] = $agenda->getFimDeSemana();
                    $medicoArr['agenda'][$coutAgenda]['horarioInicioAtendimento'] = $agenda->getHorarioInicioAtendimento();
                    $medicoArr['agenda'][$coutAgenda]['horarioFimAtendimento'] = $agenda->gethorarioFimAtendimento();
                    $medicoArr['agenda'][$coutAgenda]['horariosCriados'] = $agenda->getTortalHorariosCriados();
                    $coutAgenda++;
                }
                array_push($resultMedicos, $medicoArr);
            }

           
            $horarios = $agendaDataRepository->getAgendamentoCountByDate($request->get('start'), $request->get('end'));
            return new JsonResponse(['medico'=>$resultMedicos,'horariosAgendados'=>$horarios]);
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
    public function new(Request $request, AgendaRepository $agendaRepository, UserInterface  $user, AgendaDataStatusRepository $agendaDataStatusRepository, TranslatorInterface $translator, ValidatorInterface $validation): JsonResponse
    {
        try{
            $agendaData = new AgendaData();
            $form = $this->createForm(AgendamentoType::class, $agendaData, ['user'=>$user]);
            $form->handleRequest($request);
            
            if ($form->isValid()) {
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

                $agendaDataStatus = $agendaDataStatusRepository->findOneBy(['nome'=>'agendado']);
                $agendaData->setStatus($agendaDataStatus);
            
                $em = $this->getDoctrine()->getManager();
                $em->persist($agendaData);
                $em->flush();

                $mensagem = $translator->trans('mensagem.sucesso.novo');
                $mensagem = str_replace('_entidade','Agendamento',$mensagem);
                return new JsonResponse(
                    array(
                        'status'=>true,
                        'type'=>'success',
                        'message' => $mensagem 
                        )
                    , 200);
            }else {
                $errors = $validation->validate($agendaData);
                $errorsList = array();

                foreach ($errors as $error) {
                    $errorsList[$error->getPropertyPath()] =  $translator->trans($error->getMessage());
                }
                
                return new JsonResponse(array('status'=>false,'type'=>'danger','message' => $errorsList), 200);
            }
        }catch(\Exception $e){
            $mensagem = $translator->trans('mensagem.erro.padrao');
                
            return new JsonResponse(array('message' => $mensagem), 500);
        }
    }

    /**
     * @Route("/agendamentos-marcados", name="agendamento_marcados", methods="POST")
     */
    public function agendamentosMarcados(Request $request, TranslatorInterface $translator, AgendaDataRepository $agendaDataRepository, UserInterface  $user): JsonResponse
    {
        try{
            $params = [
                'cliente' => $user->getCliente(),
            ];

            $horariosMarcados = $agendaDataRepository->getAgendamentoByDate($request->get('start'),$request->get('end'),$params);
            
            return new JsonResponse($horariosMarcados);
        }catch(\Exception $e){
            $mensagem = $translator->trans('mensagem.erro.padrao');
                
            return new JsonResponse(array('message' => $mensagem), 500);
        }
    }

    /**
     * @Route("/buscaDetalhes", name="agendamento_busca_detalhes", methods="POST")
     */
    public function buscaDetalhes(Request $request, AgendaDataRepository $agendaDataRepository, UserInterface  $user): JsonResponse
    {
        try{
            $params = [
                'cliente'   => $user->getCliente(),
                'id'        => [
                    'operator'  => '=',
                    'value'     => $request->get('id') ,
                ],
                'result-format' => 'array'
            ];

            $horarioDetalhe = $agendaDataRepository->findByParams($params);
            return new JsonResponse($horarioDetalhe[0]);
        }catch(\Exception $e){
            return new JsonResponse($e->getMessage(),500);
        }
    }
}