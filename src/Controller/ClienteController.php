<?php

namespace App\Controller;

use App\Entity\Cliente;
use App\Form\ClienteType;
use App\Repository\ClienteRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

/**
 * @Route("/admin/cliente")
 */
class ClienteController extends Controller
{
    /**
     * @Route("/", name="cliente_index", methods="GET")
     */
    public function index(ClienteRepository $clienteRepository): Response
    {
        return $this->render('cliente/index.html.twig', ['clientes' => $clienteRepository->findAll()]);
    }

    /**
     * @Route("/novo", name="cliente_new", methods="GET|POST")
     */
    public function new(Request $request, SessionInterface $session): Response
    { 
        $cliente = new Cliente();
        $form = $this->createForm(ClienteType::class, $cliente);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($cliente);
            $em->flush();

            $session->getFlashBag()->add('success', 'mensagem.sucesso.novo');
            $session->getFlashBag()->add('_entidade', Cliente::CLASS_NAME );

            return $this->redirectToRoute('cliente_index');
        }

        return $this->render('cliente/new.html.twig', [
            'cliente' => $cliente,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="cliente_show", methods="GET")
     */
    public function show(Cliente $cliente): Response
    {
        return $this->render('cliente/show.html.twig', ['cliente' => $cliente]);
    }

    /**
     * @Route("/{id}/edit", name="cliente_edit", methods="GET|POST")
     */
    public function edit(Request $request, SessionInterface $session, Cliente $cliente): Response
    {
        $form = $this->createForm(ClienteType::class, $cliente);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            $session->getFlashBag()->add('success', 'mensagem.sucesso.editar');
            $session->getFlashBag()->add('_entidade', Cliente::CLASS_NAME );

            return $this->redirectToRoute('cliente_edit', ['id' => $cliente->getId()]);
        }

        return $this->render('cliente/edit.html.twig', [
            'cliente' => $cliente,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="cliente_delete", methods="DELETE")
     */
    public function delete(Request $request, Cliente $cliente, SessionInterface $session): Response
    {
        if ($this->isCsrfTokenValid('delete'.$cliente->getId(), $request->request->get('_token'))) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($cliente);
            $em->flush();

            $session->getFlashBag()->add('success', 'mensagem.sucesso.deletar');
            $session->getFlashBag()->add('_entidade', Cliente::CLASS_NAME );
        }

        return $this->redirectToRoute('cliente_index');
    }
}
