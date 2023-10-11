<?php

// src/DataFixtures/UserFixtures.php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use App\Entity\User;

class UserFixtures extends Fixture
{
    private $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    public function load(ObjectManager $manager)
    {
        // Création d'un utilisateur admin
        $admin = new User();
        $admin->setEmail('admin@admin.com');
        $admin->setPassword($this->passwordHasher->hashPassword($admin, 'adminadmin')); // Encodage du mot de passe
        $admin->setRoles(['ROLE_ADMIN']);
        $admin->setNom('Admin');
        $admin->setPrenom('Admin');
        $admin->setAdministrateur(true);
        $admin->setActif(true);
        $admin->setPseudo('admin');
        $admin->setTelephone(0601055467);
        $manager->persist($admin);

        // Création d'un utilisateur standard
        $user = new User();
        $user->setEmail('user@user.com');
        $user->setPassword($this->passwordHasher->hashPassword($user, 'useruser')); // Encodage du mot de passe
        $user->setRoles(['ROLE_USER']);
        $user->setNom('User');
        $user->setPrenom('User');
        $user->setAdministrateur(false);
        $user->setActif(true);
        $user->setPseudo('user');
        $user->setTelephone(0601055467);
        $manager->persist($user);

        $manager->flush();
    }
}
