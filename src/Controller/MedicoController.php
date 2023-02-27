<?php

namespace App\Controller;

use App\Entity\Medico;
use App\Form\MedicoType;
use App\Repository\MedicoRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;
use Doctrine\Persistence\ManagerRegistry;

#[Route("/medico")]
class MedicoController extends AbstractController
{
    #[Route("/", name:"medico_index", methods:"GET")]
    public function index(MedicoRepository $medicoRepository, UserInterface $user): Response
    {
        $medicos = $medicoRepository->searchResult(['cliente' => $user->getCliente()]);
        return $this->render('medico/index.html.twig', ['medicos' => $medicos]);
    }

    #[Route("/new", name:"medico_new", methods:"GET|POST")]
    public function new(Request $request, SessionInterface $session, ManagerRegistry $doctrine): Response
    {
        $medico = new Medico();
        $form = $this->createForm(MedicoType::class, $medico);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $doctrine->getManager();

            if(!empty($this->getUser()->getCliente())){
                $medico->setCliente($this->getUser()->getCliente());
            }
            $entityManager->persist($medico);
            $entityManager->flush();

            $session->getFlashBag()->add('success', 'mensagem.sucesso.novo');
            $session->getFlashBag()->add('_entidade', Medico::CLASS_NAME );

            return $this->redirectToRoute('medico_index');
        }

        return $this->render('medico/new.html.twig', [
            'medico' => $medico,
            'form' => $form->createView(),
        ]);
    }

    #[Route("/{id}", name:"medico_show", methods:"GET")]
    public function show(Medico $medico): Response
    {
        return $this->render('medico/show.html.twig', ['medico' => $medico]);
    }

    #[Route("/{id}/edit", name:"medico_edit", methods:"GET|POST")]
    public function edit(Request $request, Medico $medico, SessionInterface $session, ManagerRegistry $doctrine): Response
    {
        $form = $this->createForm(MedicoType::class, $medico);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $doctrine->getManager();

            $entityManager->persist($medico);
            $entityManager->flush();

            $session->getFlashBag()->add('success', 'mensagem.sucesso.novo');
            $session->getFlashBag()->add('_entidade', Medico::CLASS_NAME );

            return $this->redirectToRoute('medico_index');
        }

        return $this->render('medico/edit.html.twig', [
            'medico' => $medico,
            'form' => $form->createView(),
        ]);
    }

    #[Route("/{id}", name:"medico_delete", methods:"POST")]
    public function delete(Request $request, SessionInterface $session, ManagerRegistry $doctrine, Medico $medico): Response
    {
        if ($this->isCsrfTokenValid('delete'.$medico->getId(), $request->request->get('_token'))) {
            $entityManager = $doctrine->getManager();
            $entityManager->remove($medico);
            $entityManager->flush();

            $session->getFlashBag()->add('success', 'mensagem.sucesso.deletar');
            $session->getFlashBag()->add('_entidade', Medico::CLASS_NAME );
        }

        return $this->redirectToRoute('medico_index');
    }
}