<?php

namespace App\Controller;

use App\Entity\Formation;
use App\Entity\Matiere;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TuteurController extends AbstractController
{
    #[Route('/tuteur', name: 'app_tuteur_dashboard')]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $authenticatedUser = $this->getUser();
        
        if (!$authenticatedUser ||!in_array('ROLE_TUTEUR', $authenticatedUser->getRoles())) {
            return $this->redirectToRoute('app_login');
        }
        $repo= $entityManager->getRepository(User::class);
        $user= $repo->findOneByEmail($authenticatedUser->getUserIdentifier());

        
        return $this->render('tuteur/index.html.twig', [
            'user' => $user
        ]);
    }
    #[Route('/tuteur/{id}/apprenant/{apprenantId}', name: 'app_tuteur_apprenant')]
    public function apprenantMatiere(EntityManagerInterface $entityManager, int $id, int $apprenantId): Response
    {
        $userRepo= $entityManager->getRepository(User::class);
        $tuteur= $userRepo->findOneById($id);
        $apprenant= $userRepo->findOneById($apprenantId);
        $formation= $apprenant->getFormation();
        $matieres= $formation->getMatieres();


        
        return $this->render('tuteur/tuteur_apprenant.html.twig', [
            'tuteur' => $tuteur,
            'matieres' => $matieres,
            'apprenant' => $apprenant,
            'formation' => $formation
        ]);
    }

    #[Route('/tuteur/{id}/apprenant/{apprenantId}/matiere/{matiereId}', name: 'app_tuteur_apprenant_matiere')]
    public function apprenantMatiereDetail(EntityManagerInterface $entityManager, int $id, int $apprenantId, int $matiereId): Response
    {
        $userRepo= $entityManager->getRepository(User::class);
        $formationRepo= $entityManager->getRepository(Formation::class);
        $tuteur= $userRepo->findOneById($id);
        $apprenant= $userRepo->findOneById($apprenantId);
        $formation= $apprenant->getFormation();
        $matieres= $formation->getMatieres();


        
        return $this->render('tuteur/tuteur_apprenant.html.twig', [
            'tuteur' => $tuteur,
            'matieres' => $matieres,
            'apprenant' => $apprenant
        ]);
    }
}
