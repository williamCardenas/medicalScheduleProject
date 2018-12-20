<?php

namespace App\Controller;

use App\Entity\Paciente;
use App\Form\PacienteType;
use App\Repository\PacienteRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * @Route("/paciente")
 */
class PacienteController extends AbstractController
{
    /**
     * @Route("/", name="paciente_index", methods="GET")
     */
    public function index(PacienteRepository $pacienteRepository, UserInterface $user): Response
    {
        $pacientes = $pacienteRepository->searchResult(['cliente'=>$user->getCliente()]);
        return $this->render('paciente/index.html.twig', ['pacientes' => $pacientes]);
    }

    /**
     * @Route("/new", name="paciente_new", methods="GET|POST")
     */
    public function new(Request $request, SessionInterface $session, UserInterface $user): Response
    {
        $paciente = new Paciente();
        $form = $this->createForm(PacienteType::class, $paciente);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $paciente->setCliente($user->getCliente());
            $em = $this->getDoctrine()->getManager();
            $em->persist($paciente);
            $em->flush();

            $session->getFlashBag()->add('success', 'mensagem.sucesso.novo');
            $session->getFlashBag()->add('_entidade', Paciente::CLASS_NAME );

            return $this->redirectToRoute('paciente_index');
        }

        return $this->render('paciente/new.html.twig', [
            'paciente' => $paciente,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="paciente_show", methods="GET")
     */
    public function show(Paciente $paciente): Response
    {
        return $this->render('paciente/show.html.twig', ['paciente' => $paciente]);
    }

    /**
     * @Route("/{id}/edit", name="paciente_edit", methods="GET|POST")
     */
    public function edit(Request $request, Paciente $paciente, SessionInterface $session): Response
    {
        $form = $this->createForm(PacienteType::class, $paciente);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            $session->getFlashBag()->add('success', 'mensagem.sucesso.novo');
            $session->getFlashBag()->add('_entidade', Paciente::CLASS_NAME );

            return $this->redirectToRoute('paciente_edit', ['id' => $paciente->getId()]);
        }

        return $this->render('paciente/edit.html.twig', [
            'paciente' => $paciente,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="paciente_delete", methods="DELETE")
     */
    public function delete(Request $request, Paciente $paciente, SessionInterface $session): Response
    {
        if ($this->isCsrfTokenValid('delete'.$paciente->getId(), $request->request->get('_token'))) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($paciente);
            $em->flush();

            $session->getFlashBag()->add('success', 'mensagem.sucesso.deletar');
            $session->getFlashBag()->add('_entidade', Paciente::CLASS_NAME );
        }

        return $this->redirectToRoute('paciente_index');
    }

    /**
     * @Route("/buscar", name="paciente_busca", methods="POST")
     */
    public function busca(Request $request, PacienteRepository $pacienteRepository, SessionInterface $session, UserInterface $user): Response
    {
        
            $paciente = $pacienteRepository->searchArrayResult([
                'cliente'       => $user->getCliente(),
                'pesquisa'       => $request->get('q')
            ]);
            return new JsonResponse($paciente);
        
    }
}
