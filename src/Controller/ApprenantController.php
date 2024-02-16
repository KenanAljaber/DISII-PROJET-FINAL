<?php

namespace App\Controller;

use App\Entity\Matiere;
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
                'apprenant_id' => $result['apprenant_id'],
                'formation' => $result['formation'],
                'formateur' => $result['formateur'],
                'nom' => $result['matiere'],
                'id' => $result['matiere_id'],
            ];
        }

        return $this->render('apprenant/index.html.twig', [
            'user' => $user,
            'matieres' => $matieres,
        ]);
    }
    #[Route('/apprenant/{id}/{matiereId}', name: 'apprenant_show_matiere')]
    public function showMatiereDetails(EntityManagerInterface $entityManager, int $id, int $matiereId): Response
    {
        $userRepo= $entityManager->getRepository(User::class);
        $matiereRepo= $entityManager->getRepository(Matiere::class);

        $user = $userRepo->find($id);

        $matiere = $matiereRepo->find($matiereId);
        $notes= $user->getNotes();
        $filteredNotes = [];
        foreach ($notes as $note) {
            if ($note->getMatiere()->getId() === $matiereId) {
                $filteredNotes[] = $note;

            }
        }
        
        return $this->render('apprenant/show_matiere.html.twig', [
            'user' => $user,
            'matiere' => $matiere,
            'notes' => $filteredNotes
        ]);

    }

}
