<?php

namespace App\Controller;

use App\Entity\Cliente;
use App\Form\ClienteType;
use App\Repository\ClienteRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Doctrine\Persistence\ManagerRegistry;

#[Route("/admin/cliente")]
class ClienteController extends AbstractController
{
    #[Route("/", name:"cliente_index", methods:"GET")]
    public function index(ClienteRepository $clienteRepository): Response
    {
        return $this->render('cliente/index.html.twig', ['clientes' => $clienteRepository->findAll()]);
    }

    #[Route("/novo", name:"cliente_new", methods:"GET|POST")]
    public function new(Request $request, SessionInterface $session, ManagerRegistry $doctrine): Response
    { 
        $cliente = new Cliente();
        $form = $this->createForm(ClienteType::class, $cliente);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $doctrine->getManager();

            $entityManager->persist($cliente);
            $entityManager->flush();

            $session->getFlashBag()->add('success', 'mensagem.sucesso.novo');
            $session->getFlashBag()->add('_entidade', Cliente::CLASS_NAME );

            return $this->redirectToRoute('cliente_index');
        }

        return $this->render('cliente/new.html.twig', [
            'cliente' => $cliente,
            'form' => $form->createView(),
        ]);
    }

    #[Route("/{id}", name:"cliente_show", methods:"GET")]
    public function show(Cliente $cliente): Response
    {
        return $this->render('cliente/show.html.twig', ['cliente' => $cliente]);
    }

    #[Route("/{id}/edit", name:"cliente_edit", methods:"GET|POST")]
    public function edit(Request $request, SessionInterface $session, Cliente $cliente, ManagerRegistry $doctrine): Response
    {
        $form = $this->createForm(ClienteType::class, $cliente);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $doctrine->getManager();

            $entityManager->persist($cliente);
            $entityManager->flush();

            $session->getFlashBag()->add('success', 'mensagem.sucesso.editado');
            $session->getFlashBag()->add('_entidade', Cliente::CLASS_NAME );

            return $this->redirectToRoute('cliente_edit', ['id' => $cliente->getId()]);
        }

        return $this->render('cliente/edit.html.twig', [
            'cliente' => $cliente,
            'form' => $form->createView(),
        ]);
    }

    #[Route("/delete/{id}", name:"cliente_delete", methods:"POST")]
    public function delete(Request $request, Cliente $cliente, SessionInterface $session, ManagerRegistry $doctrine): Response
    {
        if ($this->isCsrfTokenValid('delete'.$cliente->getId(), $request->request->get('_token'))) {
            $entityManager = $doctrine->getManager();

            $entityManager->remove($cliente);
            $entityManager->flush();

            $session->getFlashBag()->add('success', 'mensagem.sucesso.deletar');
            $session->getFlashBag()->add('_entidade', Cliente::CLASS_NAME );
        }

        return $this->redirectToRoute('cliente_index');
    }
}
