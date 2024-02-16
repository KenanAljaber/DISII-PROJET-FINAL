<?php

namespace App\Controller;
require_once __DIR__ . '/../constants/constants.php';


use App\Entity\User;
use App\Form\UserType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    #[Route(path: '/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        if ($this->getUser()) {
            $role=$this->getUser()->getRoles()[0];
            if($role==VISTIANTE){
                
                return $this->render('apprenant/pending.html.twig', ['user' => $this->getUser()]);
            }
            if($role==APPRENANT){
                return $this->redirectToRoute('app_apprenant_dashboard');
            }
            if($role==ADMIN){
                return $this->render('admin/index.html.twig', ['user' => $this->getUser()]);
            }
            if($role==TUTEUR){
                return $this->render('tuteur/index.html.twig', ['user' => $this->getUser()]);
            }
            return $this->redirectToRoute('app_home');
        }
        
        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();
        

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    #[Route(path: '/signup', name: 'app_signup')]
    public function signup(EntityManagerInterface $entityManager, Request $request, UserPasswordHasherInterface $userPasswordHasher): Response
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $hashedPassword = $userPasswordHasher->hashPassword(
                $user,
                $user->getPassword()
            );

            $user->setPassword($hashedPassword);
            $user->setRoles([VISTIANTE]);
            $entityManager->persist($user);
            $entityManager->flush();
            return $this->redirectToRoute('app_login');
        }

        return $this->render('security/signup.html.twig',
        [
            "user" => $user,
            "form" => $form
        ]);
    }

    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }
}
