<?php

namespace App\DataFixtures;

require_once __DIR__ . '/../constants/constants.php';


use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{

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
            $manager->persist($user);
        }

        $manager->flush();
    }
}
