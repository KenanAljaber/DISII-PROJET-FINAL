<?php

namespace App\Controller;

use App\Entity\Matiere;
use App\Entity\User;
use App\Form\UserType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class AdminController extends AbstractController
{
    #[Route('/admin', name: 'app_admin')]
    public function index(): Response
    {
        return $this->render('admin/index.html.twig', [
            'controller_name' => 'AdminController',
        ]);
    }

    #[Route('/admin/ajouteFormateur', name: 'app_admin_ajouteFormateur')]
    public function ajouteFormateur(EntityManagerInterface $entityManager, Request $request, UserPasswordHasherInterface $userPasswordHasher): Response
    {
        $matiereRepo= $entityManager->getRepository(Matiere::class);
        $userRepo= $entityManager->getRepository(User::class);
        $matieres = $matiereRepo->findAll();
        $formateures = $userRepo->findUsersByRole('ROLE_FORMATEUR');


        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $hashedPassword = $userPasswordHasher->hashPassword(
                $user,
                $user->getPassword()
            );
            $user->setPassword($hashedPassword);
            $user->setRoles(['ROLE_FORMATEUR']);
            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute('app_admin_ajouteFormateur');
            
        }

        return $this->render('admin/ajouteFormateur.html.twig', [
            'matieres' => $matieres,
            'formateures' => $formateures,
            'form' => $form,
            'user' => $user
        ]);
    }
// list of mateier 
// for each matiers show list of apprenant
}
