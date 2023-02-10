<?php

namespace App\Controller;

use App\Entity\Clinica;
use App\Form\ClinicaType;
use App\Repository\ClinicaRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;


#[Route("/admin/clinica")]
class ClinicaController extends AbstractController
{
    #[Route("/", name:"clinica_index", methods:"GET")]
    public function index(ClinicaRepository $clinicaRepository): Response
    {
        return $this->render('clinica/index.html.twig', ['clinicas' => $clinicaRepository->findAll()]);
    }

    #[Route("/new", name:"clinica_new", methods:"GET|POST")]
    public function new(Request $request, SessionInterface $session): Response
    {
        $clinica = new Clinica();
        $form = $this->createForm(ClinicaType::class, $clinica);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($clinica);
            $em->flush();

            $session->getFlashBag()->add('success', 'mensagem.sucesso.novo');
            $session->getFlashBag()->add('_entidade', Clinica::CLASS_NAME );

            return $this->redirectToRoute('clinica_index');
        }

        return $this->render('clinica/new.html.twig', [
            'clinica' => $clinica,
            'form' => $form->createView(),
        ]);
    }

    #[Route("/{id}", name:"clinica_show", methods:"GET")]
    public function show(Clinica $clinica): Response
    {
        return $this->render('clinica/show.html.twig', ['clinica' => $clinica]);
    }

    #[Route("/{id}/edit", name:"clinica_edit", methods:"GET|POST")]
    public function edit(Request $request, SessionInterface $session, Clinica $clinica): Response
    {
        $form = $this->createForm(ClinicaType::class, $clinica);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            $session->getFlashBag()->add('success', 'mensagem.sucesso.editado');
            $session->getFlashBag()->add('_entidade', Clinica::CLASS_NAME );

            return $this->redirectToRoute('clinica_edit', ['id' => $clinica->getId()]);
        }

        return $this->render('clinica/edit.html.twig', [
            'clinica' => $clinica,
            'form' => $form->createView(),
        ]);
    }

    #[Route("/{id}", name:"clinica_delete", methods:"DELETE")]
    public function delete(Request $request, SessionInterface $session, Clinica $clinica): Response
    {
        if ($this->isCsrfTokenValid('delete'.$clinica->getId(), $request->request->get('_token'))) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($clinica);
            $em->flush();

            $session->getFlashBag()->add('success', 'mensagem.sucesso.deletar');
            $session->getFlashBag()->add('_entidade', Clinica::CLASS_NAME );
        }

        return $this->redirectToRoute('clinica_index');
    }
}
