<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use App\Security\Voter\AdminVoter;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Doctrine\DBAL\Driver\PDOException;

/**
 * @Route("/admin/usuario")
 */
class UserController extends Controller
{
    /**
     * @Route("/", name="user_index", methods="GET")
     */
    public function index(UserRepository $userRepository): Response
    {
        $this->denyAccessUnlessGranted('LIST',new User());

        return $this->render('user/index.html.twig', ['users' => $userRepository->findAll()]);
    }

    /**
     * @Route("/new", name="user_new", methods="GET|POST")
     */
    public function new(Request $request, UserPasswordEncoderInterface $encode,  SessionInterface $session): Response
    {
        
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            try{
                $user->setRoles(array(AdminVoter::ADMIN));
                $encoded = $encode->encodePassword($user, $user->getPassword());
                $user->setPassword($encoded);
                
                $em = $this->getDoctrine()->getManager();
                $em->persist($user);
                $em->flush();
                
                $session->getFlashBag()->add('success', 'mensagem.sucesso.novo');
                $session->getFlashBag()->add('_entidade', User::CLASS_NAME );

                return $this->redirectToRoute('user_index');
            }catch(UniqueConstraintViolationException $ex){
                $session->getFlashBag()->add('error', 'mensagem.banco.erro.campoUnico');
                $session->getFlashBag()->add('_entidade', User::CLASS_NAME );
            }catch(PDOException $ex){
                $session->getFlashBag()->add('error', 'mensagem.banco.erro.generico');
                $session->getFlashBag()->add('_entidade', User::CLASS_NAME );
            }
            
        }else{
            $form->get('isAdmin')->setData(true);
        }
            
            return $this->render('user/new.html.twig', [
                'user' => $user,
                'form' => $form->createView(),
            'adminArea' => true
        ]);
    }

    /**
     * @Route("/{id}", name="user_show", methods="GET")
     */
    public function show(User $user): Response
    {
        return $this->render('user/show.html.twig', ['user' => $user]);
    }

    /**
     * @Route("/{id}/edit", name="user_edit", methods="GET|POST")
     */
    public function edit(Request $request, User $user): Response
    {
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('user_edit', ['id' => $user->getId()]);
        }

        return $this->render('user/edit.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="user_delete", methods="DELETE")
     */
    public function delete(Request $request, User $user): Response
    {
        if ($this->isCsrfTokenValid('delete'.$user->getId(), $request->request->get('_token'))) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($user);
            $em->flush();
        }

        return $this->redirectToRoute('user_index');
    }
}
