<?php

namespace App\Controller;

use App\Entity\Cliente;
use App\Entity\User;
use App\Form\UserType;
use App\Repository\ClienteRepository;
use App\Repository\UserRepository;
use App\Security\Voter\AdminVoter;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Doctrine\Persistence\ManagerRegistry;

use Exception;

#[Route("/admin/cliente/{clienteId}/usuario")]
class ClienteUserController extends AbstractController
{
    #[Route("/", name:"cliente_user_index", methods:"GET")]
    public function index(ClienteRepository $clienteRepository, UserRepository $userRepository, $clienteId): Response
    {
        $cliente = $clienteRepository->find($clienteId);
        $usuarios = $userRepository->findBy(['clienteId'=> $cliente->getId()]);
        return $this->render('clienteUser/index.html.twig', ['cliente' => $cliente, 'usuarios'=> $usuarios]);
    }

    #[Route("/novo", name:"cliente_user_new", methods:"GET|POST")]
    public function new(Request $request, UserPasswordHasherInterface $passwordHasher, ClienteRepository $clienteRepository, SessionInterface $session, ManagerRegistry $doctrine, $clienteId): Response
    { 
        $cliente = $clienteRepository->find($clienteId);
        $user = new User();
        $user->setCliente($cliente);
        $form = $this->createForm(UserType::class, $user);
        
        $form->handleRequest($request);
        if ($form->isSubmitted()){
            if ($form->isValid()) {
                try{
                    $user->setRoles(array(AdminVoter::CLIENT_USER));
                    $hashedPassword = $passwordHasher->hashPassword($user, $user->getPassword());
                    $user->setPassword($hashedPassword);
                    
                    $entityManager = $doctrine->getManager();
                    $entityManager->persist($user);
                    $entityManager->flush();
                    
                    $session->getFlashBag()->add('success', 'mensagem.sucesso.novo');
                    $session->getFlashBag()->add('_entidade', User::CLASS_NAME );
    
                    return $this->redirectToRoute('cliente_user_index',['clienteId'=> $cliente->getId()]);
                }catch(UniqueConstraintViolationException $ex){
                    $session->getFlashBag()->add('error', 'mensagem.banco.erro.campoUnico');
                    $session->getFlashBag()->add('_entidade', User::CLASS_NAME );
                }catch(PDOException $ex){
                    $session->getFlashBag()->add('error', 'mensagem.banco.erro.generico');
                    $session->getFlashBag()->add('_entidade', User::CLASS_NAME );
                }
            }
        }

        return $this->render('clienteUser/new.html.twig', [
            'cliente' => $cliente,
            'form' => $form->createView(),
        ]);
    }

    #[Route("/{id}", name:"cliente_user_show", methods:"GET")]
    public function show(User $user, Request $request, ClienteRepository $clienteRepository, $clienteId): Response
    {
        $cliente = $clienteRepository->find($clienteId);
        
        return $this->render('clienteUser/show.html.twig', ['cliente' => $cliente, 'user' => $user]);
    }

    #[Route("/{id}/edit", name:"cliente_user_edit", methods:"GET|POST")]
    public function edit(Request $request, UserPasswordHasherInterface $passwordHasher, ClienteRepository $clienteRepository, SessionInterface $session, User $user, $clienteId, ManagerRegistry $doctrine): Response
    {
        $cliente = $clienteRepository->find($clienteId);
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            
            try{
                $user = $form->getData();
                $hashedPassword = $passwordHasher->hashPassword($user, $user->getPassword());
                $user->setPassword($hashedPassword);
                
                $entityManager = $doctrine->getManager();
                $entityManager->persist($user);
                $entityManager->flush();
                
                $session->getFlashBag()->add('success', 'mensagem.sucesso.editado');
                $session->getFlashBag()->add('_entidade', User::CLASS_NAME );

                return $this->redirectToRoute('cliente_user_edit', ['clienteId' => $cliente->getId(),'id'=>$user->getId()]);
            }catch(UniqueConstraintViolationException $ex){
                $session->getFlashBag()->add('error', 'mensagem.banco.erro.campoUnico');
                $session->getFlashBag()->add('_entidade', User::CLASS_NAME );
            }catch(PDOException $ex){
                $session->getFlashBag()->add('error', 'mensagem.banco.erro.generico');
                $session->getFlashBag()->add('_entidade', User::CLASS_NAME );
            }

        }

        return $this->render('clienteUser/edit.html.twig', [
            'cliente' => $cliente,
            'form' => $form->createView(),
        ]);
    }

    #[Route("/{id}/delete", name:"cliente_user_delete", methods:"POST")]
    public function delete(Request $request, User $user, SessionInterface $session, $clienteId, ManagerRegistry $doctrine): Response
    {
        try{
            if ($this->isCsrfTokenValid('delete'.$user->getId(), $request->request->get('_token'))) {
                $entityManager = $doctrine->getManager();
                $entityManager->remove($user);
                $entityManager->flush();
    
                $session->getFlashBag()->add('success', 'mensagem.sucesso.deletar');
                $session->getFlashBag()->add('_entidade', User::CLASS_NAME );
            }
        }catch(Exception $ex){
            $session->getFlashBag()->add('error', 'mensagem.banco.erro.generico');
            $session->getFlashBag()->add('_entidade', User::CLASS_NAME );
        }

        return $this->redirectToRoute('cliente_user_index',['clienteId'=>$clienteId]);
    }
}
