<?php

namespace App\service;

use App\Entity\HistoStories;
use App\Entity\Sortie;
use App\Repository\HistoStoriesRepository;
use App\Repository\SortieRepository;
use App\utils\EtatEnum;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Serializer\Encoder\JsonEncoder;

class MainService
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager,
                                HistoStoriesRepository $histoStoriesRepository,
                                SortieRepository $sortieRepository)
    {
        $this->entityManager = $entityManager;
    }

    public function updateSortieEtat(Sortie $sortie, \DateTime $today)
    {
        if ($sortie->getDateDebut() !== null &&
            $sortie->getDateDebut()->diff($today)->m >= 1) {
            $histoStories = new HistoStories();
            $histoStories->setNomSortie($sortie->getNom());
            $adresseSortie = ($sortie->getLieu()->__toString());
            $histoStories->setAdresseSortie($adresseSortie);

            $histoStories->setDateHeureDebut($sortie->getDateDebut());
            $histoStories->setDuree($sortie->getDuree());
            $histoStories->setDateLimiteInscription($sortie->getDateCloture());
            $histoStories->setNbInscriptionsMax($sortie->getNbInscriptionsMax());
            $histoStories->setInfosSortie($sortie->getDescriptioninfos());
            if ($sortie->getMotifAnnulation() !== null) {
                $histoStories->setMotifAnnulation($sortie->getMotifAnnulation());
            }
            $histoStories->setLibelleEtat($sortie->getEtat());

            $histoStories->setNomOrganisateur($sortie->getAuteur()->getNom());
            $histoStories->setPrenomOrganisateur($sortie->getAuteur()->getPrenom());
            $histoStories->setTelOrganisateur($sortie->getAuteur()->getTelephone());
            $histoStories->setEmailOrganisateur($sortie->getAuteur()->getEmail());
            $histoStories->setPseudoOrganisateur($sortie->getAuteur()->getPseudo());
            $histoStories->setSiteOrganisateur($sortie->getAuteur()->getSite()->getNom());
            $usersArray = $this->getUsersArray($sortie);
            $histoStories->setParticipants($usersArray);
            $this->entityManager->persist($histoStories);
            $this->entityManager->remove($sortie);
            $this->entityManager->flush();
        }
    }

    /**
     * @param Sortie $sortie
     * @return array
     */
    public function getUsersArray(Sortie $sortie): array
    {
        $usersArray = [];
        foreach ($sortie->getUsersInscrits()->getValues() as $user) {
            $usersArray[] = [
                'pseudo' => $user->getPseudo(),
                'nom' => $user->getNom(),
                'prenom' => $user->getPrenom(),
                'telephone' => $user->getTelephone(),
                'email' => $user->getEmail(),
                'site' => $user->getSite()->getNom(),
            ];
        }
        return $usersArray;
    }

}