<?php

namespace App\DataFixtures;

use App\Entity\Etat;
use App\Entity\Lieu;
use App\Entity\Ville;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $etatsData = [
            ['libelle' => 'Créée'],
            ['libelle' => 'Ouverte'],
            ['libelle' => 'Cloturée'],
            ['libelle' => 'En cours'],
            ['libelle' => 'Annulée'],
            ['libelle' => 'Passée']
        ];

        foreach ($etatsData as $data) {
            $etat = new Etat();
            $etat->setLibelle($data['libelle']);
            $manager->persist($etat);
        }
        // Création de villes
        $villesData = [
            ['nom' => 'Nantes', 'codePostal' => '44000'],
            ['nom' => 'Niort', 'codePostal' => '79000'],
            ['nom' => 'Quimper', 'codePostal' => '29000'],
        ];

        foreach ($villesData as $data) {
            $ville = new Ville();
            $ville->setNom($data['nom']);
            $ville->setCodePostal($data['codePostal']);
            $manager->persist($ville);
        }

        $manager->flush();
    }
}
