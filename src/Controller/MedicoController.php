<?php

namespace App\Controller;

use App\Entity\Medico;
use App\Form\MedicoType;
use App\Repository\MedicoRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/medico")
 */
class MedicoController extends Controller
{
    /**
     * @Route("/", name="medico_index", methods="GET")
     */
    public function index(MedicoRepository $medicoRepository): Response
    {
        return $this->render('medico/index.html.twig', ['medicos' => $medicoRepository->findAll()]);
    }

    /**
     * @Route("/new", name="medico_new", methods="GET|POST")
     */
    public function new(Request $request, SessionInterface $session): Response
    {
        $medico = new Medico();
        $form = $this->createForm(MedicoType::class, $medico);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();

            if(!empty($this->getUser()->getCliente())){
                $medico->setCliente($this->getUser()->getCliente());
            }
            $em->persist($medico);
            $em->flush();

            $session->getFlashBag()->add('success', 'mensagem.sucesso.novo');
            $session->getFlashBag()->add('_entidade', Medico::CLASS_NAME );

            return $this->redirectToRoute('medico_index');
        }

        return $this->render('medico/new.html.twig', [
            'medico' => $medico,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="medico_show", methods="GET")
     */
    public function show(Medico $medico): Response
    {
        return $this->render('medico/show.html.twig', ['medico' => $medico]);
    }

    /**
     * @Route("/{id}/edit", name="medico_edit", methods="GET|POST")
     */
    public function edit(Request $request, Medico $medico): Response
    {
        $form = $this->createForm(MedicoType::class, $medico);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('medico_edit', ['id' => $medico->getId()]);
        }

        return $this->render('medico/edit.html.twig', [
            'medico' => $medico,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="medico_delete", methods="DELETE")
     */
    public function delete(Request $request, Medico $medico): Response
    {
        if ($this->isCsrfTokenValid('delete'.$medico->getId(), $request->request->get('_token'))) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($medico);
            $em->flush();
        }

        return $this->redirectToRoute('medico_index');
    }
}
