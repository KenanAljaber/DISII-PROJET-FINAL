<?php

namespace App\DataFixtures;

require_once __DIR__ . '/../constants/constants.php';

use App\Entity\Formation;
use App\Entity\Matiere;
use App\Entity\Program;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    private $fake_matiere = ["Anglais", "Francais", "Maths", "Physique", "Chimie", "Biologie", "SVT", "Histoire", "Geographie", "Geologie", "Sociologie", "Economie", "Psychologie", "Programmation", "Algorithm"];
    private $fake_formation = ["Developpeur Web", "Developpeur Mobile", "Ingénieur Logiciels", "Ingénieur Réseaux", "Ingénieur Multimédia", "Ingénieur Cybersecurite", " Ingénieur Génie Logiciel"];
    private $fake_program = ["chapter 1", "chapter 2", "chapter 3", "chapter 4", "chapter 5", "chapter 6", "chapter 7", "chapter 8", "chapter 9", "chapter 10"];
    private $passwordHasher;
    private $faker;

    public function __construct(UserPasswordHasherInterface $passwordHasherInterface)
    {
        $this->passwordHasher = $passwordHasherInterface;
        $this->faker = Factory::create();
    }
    public function load(ObjectManager $manager): void
    {
        // Create admin user
        $formation_objects = $this->loadFormations($manager);
        $this->loadUsers($manager, $formation_objects);

        $manager->flush();
    }

    public function loadUsers(ObjectManager $manager, $formations): void
    {
        $admin = new User();
        $admin->setEmail('admin@admin.com');
        $admin->setNom('Admin');
        $admin->setPrenom('Admin');
        $admin->setRoles([ADMIN]);
        $admin->setPassword('Admin0009');
        $hashedPassword = $this->passwordHasher->hashPassword(
            $admin,
            $admin->getPassword()
        );
        $admin->setPassword($hashedPassword);
        $manager->persist($admin);

        $tuteur = new User();
        $tuteur->setEmail('tuteur@tuteur.com');
        $tuteur->setNom('Tuteur');
        $tuteur->setPrenom('Tuteur');
        $tuteur->setRoles([TUTEUR]);
        $tuteur->setPassword('Tuteur0009');
        $hashedPassword = $this->passwordHasher->hashPassword(
            $tuteur,
            $tuteur->getPassword()
        );
        $tuteur->setPassword($hashedPassword);


        // Create multiple fake users with apprenant role
        for ($i = 0; $i < 10; $i++) {
            $user = new User();
            $user->setEmail($this->faker->email);
            $user->setNom($this->faker->lastName);
            $user->setPrenom($this->faker->firstName);
            $user->setRoles([APPRENANT]);
            $user->setPassword('password');
            $hashedPassword = $this->passwordHasher->hashPassword(
                $user,
                $user->getPassword()
            );
            $user->setPassword($hashedPassword);
            $randomFormation = $this->faker->randomElement($formations);
            $user->setFormation($randomFormation);
            $manager->persist($user);
            if ($i % 2 == 0) {
                $user->setTuteur($tuteur);
                $tuteur->addApprenant($user);
            }
        }
        $manager->persist($tuteur);
    }
    public function loadFormations(ObjectManager $manager): array
    {
       $formateur= new User();
       $formateur->setEmail('formateur@formateur.com');
       $formateur->setNom('Formateur');
       $formateur->setPrenom('Formateur');
       $formateur->setRoles([FORMATEUR]);
       $formateur->setPassword('Admin0009');
       $hashedPassword = $this->passwordHasher->hashPassword(
           $formateur,
           $formateur->getPassword()
       );
       $formateur->setPassword($hashedPassword);
       $manager->persist($formateur);
        $formation_objects = [];
        for ($i = 0; $i < 5; $i++) {
            $formation = new Formation();
            $added_formation = [];
            $formation_nom = $this->faker->randomElement($this->fake_formation);
            if (in_array($formation_nom, $added_formation)) {
                $i--;
                continue;
            }
            $added_formation[] = $formation_nom;
            $formation->setTitre($formation_nom);
            for ($j = 0; $j < 5; $j++) {
                $matiere = new Matiere();
                $matiere->setNom($this->faker->randomElement($this->fake_matiere));
                $matiere->setFormation($formation);
                $matiere->setFormateur($formateur);
                for($k = 0; $k < 5; $k++){
                    $progrm = new Program();
                    $progrm->setTitre($this->faker->randomElement($this->fake_program));
                    $progrm->setDate($this->faker->date('Y-m-d'));
                    $progrm->setMatiere($matiere);
                    $matiere->addProgram($progrm);
                    $manager->persist($progrm);
                }
                $manager->persist($matiere);
            }

            $manager->persist($formation);

            $formation_objects[] = $formation;
        }
        $manager->flush();
        return $formation_objects;
    }
}
