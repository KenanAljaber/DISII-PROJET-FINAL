<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ApprenantController extends AbstractController
{
    #[Route('/apprenant/dashboard', name: 'app_apprenant_dashboard')]
    public function index(): Response
    {
        $user = $this->getUser();
        if (!$user ||!in_array('ROLE_APPRENANT', $user->getRoles())) {
            return $this->redirectToRoute('app_login');
        }

        
        return $this->render('apprenant/index.html.twig', [
            'user' => $user,
        ]);
    }

  
}
