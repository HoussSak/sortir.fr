<?php

namespace App\DataFixtures;

use App\Entity\Lieu;
use App\Entity\Ville;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;

class LieuFixtures extends  Fixture 
{

    public function load(ObjectManager $manager)
    {
        $niort = new Ville();
        $niort->setNom('Niort');
        $niort->setCodePostal('79000');
        $manager->persist($niort);

        $nantes = new Ville();
        $nantes->setNom('Nantes');
        $nantes->setCodePostal('44000');
        $manager->persist($nantes);

        $quimper = new Ville();
        $quimper->setNom('Quimper');
        $quimper->setCodePostal('29000');
        $manager->persist($quimper);

        $manager->flush();


        $villes = $manager->getRepository(Ville::class)->findAll();

        $lieuxData = [
            [
                'nom' => 'Les Remparts',
                'rue' => 'Place de la Brèche',
                'latitude' => 48.858844,
                'longitude' => 2.294351,
                'ville' => $villes[0],
            ],
            [
                'nom' => 'Bowling',
                'rue' => 'rue de Nantes',
                'latitude' => 51.507222,
                'longitude' => -0.12750,
                'ville' => $villes[1],
            ],
            [
                'nom' => 'Cinéma',
                'rue' => 'rue de Quimper',
                'latitude' => 51.507222,
                'longitude' => -0.12750,
                'ville' => $villes[2],
            ],
        ];

        foreach ($lieuxData as $data) {
            $lieu = new Lieu();
            $lieu->setNom($data['nom']);
            $lieu->setRue($data['rue']);
            $lieu->setLatitude($data['latitude']);
            $lieu->setLongitude($data['longitude']);
            $lieu->setVille($data['ville']);
            $manager->persist($lieu);
        }

        $manager->flush();




    }

}