<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(): Response
    {
        if ($this->getUser()) {
            if ($this->getUser()->getRoles()[0] === ADMIN) {
            }
            if ($this->getUser()->getRoles()[0] === APPRENANT) {

                return $this->redirectToRoute('app_apprenant_dashboard');
            }
            if ($this->getUser()->getRoles()[0] === TUTEUR) {

                return $this->redirectToRoute('app_tuteur_dashboard');
            }
        }
        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }
}
