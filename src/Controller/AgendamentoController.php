<?php

namespace App\Controller;

use App\Entity\Agenda;
use App\Entity\Medico;
use App\Entity\AgendaConfig;
use App\Entity\Cliente;
use App\Form\AgendaType;
use App\Repository\AgendaRepository;
use App\Repository\MedicoRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Security\Core\User\UserInterface;

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
        dump($medicos);
        return $this->render('agendamento/index.html.twig', ['medicos' => $medicos]);
    }

}