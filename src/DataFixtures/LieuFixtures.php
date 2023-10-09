<?php

namespace App\DataFixtures;

use App\Entity\Lieu;
use App\Entity\Ville;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class LieuFixtures extends  Fixture
{
    public function load(ObjectManager $manager)
    {
        $villes = $manager->getRepository(Ville::class)->findAll();

        $lieuxData = [
            [
                'nom' => 'Lieu 1',
                'rue' => 'Rue 1',
                'latitude' => 48.858844,
                'longitude' => 2.294351,
                'ville' => $villes[0],
            ],
            [
                'nom' => 'Lieu 2',
                'rue' => 'Rue 2',
                'latitude' => 51.507222,
                'longitude' => -0.12750,
                'ville' => $villes[1],
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