<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ApprenantController extends AbstractController
{
    #[Route('/apprenant/dashboard', name: 'app_apprenant_dashboard')]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();
        if (!$user ||!in_array('ROLE_APPRENANT', $user->getRoles())) {
            return $this->redirectToRoute('app_login');
        }
        $userRepo = $entityManager->getRepository(User::class);
        $results = $userRepo->findMatiereAndFormateurByApprenantEmail($user->getUserIdentifier());
      
        $matieres = [];
        foreach ($results as $result) {
            $matieres[] = [
                'formation' => $result['formation'],
                'formateur' => $result['formateur'],
                'nom' => $result['matiere'],
            ];
        }

        return $this->render('apprenant/index.html.twig', [
            'user' => $user,
            'matieres' => $matieres,
        ]);
    }

}
