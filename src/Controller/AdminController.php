<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

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
    public function ajouteFormateur(): Response
    {
        return $this->render('admin/ajouteFormateur.html.twig', [
  
        ]);
    }

    #[Route('/admin/showusers', name: 'app_admin_showusers')]
    public function showUsers(EntityManagerInterface $entityManager): Response
    {
        $userRepo = $entityManager->getRepository(User::class);
        $users = $userRepo->findAll();
        return $this->render('admin/userShow.html.twig', [
            'users' => $users
        ]);
    }
}
