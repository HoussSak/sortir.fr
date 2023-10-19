<?php

namespace App\Controller;

use App\Entity\Sortie;
use App\Entity\User;
use App\Form\AnnulationType;
use App\Form\SortieType;
use App\Repository\EtatRepository;
use App\Repository\LieuRepository;
use App\Repository\SortieRepository;
use App\Repository\VilleRepository;
use App\utils\EtatEnum;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SortieController extends AbstractController
{

    #[Route("/sortie/creer", name: "app_sortie_creer")]
    public function creerSortie(Request $request,
                                EntityManagerInterface $entityManager,
                                SortieRepository $sortieRepository,
    LieuRepository $lieuRepository): Response
    {
        $user=$this->getUser();
        $site=$user->getSite();

        $sortie = new Sortie();
        $sortie->setSite($site);
        $sortie->setEtat(EtatEnum::CREEE);
        $sortie->setAuteur($user);

        $sortieForm = $this->createForm(SortieType::class, $sortie);
        $sortieForm->handleRequest($request);

            if ($sortieForm->isSubmitted() && $sortieForm->isValid() )
            {
                $env= $request->request->get('creer');
                $publier='publier';

                if($env=== $publier) {
                    $sortie->setEtat(EtatEnum::OUVERTE);
                    $entityManager->persist($sortie);
                    $entityManager->flush();
                    $this->addFlash('success', 'Votre Sortie à été créee et publiée avec succes!');
                    return $this->redirectToRoute('app_home');
                }

            $entityManager->persist($sortie);
            $entityManager->flush();

            // do anything else you need here, like send an email
            $this->addFlash('success', 'Votre Sortie à été crée avec succes!');
            return $this->redirectToRoute('app_home');
        }

        return $this->render('sortie/creer.html.twig', [
            'sortieForm' => $sortieForm->createView()
        ]);
    }



    #[Route("/lieux-villes", name: "api_lieux_villes")]
    public function getAllVilleApi(VilleRepository $villeRepository): Response
    {
        $villes = $villeRepository->findAll();
        $response = [];

        foreach ($villes as $ville) {
            $lieux = [];
            foreach ($ville->getLieux() as $lieu) {
                $lieux[] = [
                    'id' => $lieu->getId(),
                    'nom' => $lieu->getNom(),
                ];
            }

            $response[] = [
                'ville' => [
                    'id' => $ville->getId(),
                    'nom' => $ville->getNom(),
                    'codePostal' => $ville->getCodePostal(),
                ],
                'lieux' => $lieux,
            ];
        }

        // Réponse au format JSON
        return $this->json($response, 200, [], ['groups' => 'lieu']);
    }



    #[Route("/sortie/afficher/{id<[0-9]+>}", name: "app_sortie_afficher")]
    public function afficher(Sortie $sortie): Response
    {
        return $this->render('sortie/afficher.html.twig', compact('sortie'));
    }

    #[Route("/sortie/modifier/{id<[0-9]+>}", name: "app_sortie_modifier")]
    public function modifierSortie(Request $request,EntityManagerInterface $entityManager,
                                   SortieRepository $sortieRepository,
                                   $id): Response
    {
        $user=$this->getUser();
        $sortie =$sortieRepository->find($id);

        $sortieForm = $this->createForm(SortieType::class, $sortie);
        $sortieForm->handleRequest($request);
    
        if ($sortieForm->isSubmitted() && $sortieForm->isValid()) {
            $dateDebut = $sortie->getDateDebut();
            $dateCloture = $sortie->getDateCloture();
    
            if ($dateCloture >= $dateDebut) {
                $this->addFlash('error', 'La date de clôture doit être strictement inférieure à la date de début.');
                return $this->redirectToRoute('app_sortie_modifier', ['id' => $id]);
            }
    
            $env = $request->request->get('modifier');
    
            $publier = 'publier';
            $supprimer = 'supprimer';
    
            if ($env === $publier) {
                $sortie->setEtat(EtatEnum::OUVERTE);
                $entityManager->persist($sortie);
                $entityManager->flush();
                $this->addFlash('success', 'Votre Sortie à été publiée avec succes!');
                return $this->redirectToRoute('app_home');
            } elseif ($env === $supprimer) {
                $entityManager->remove($sortie);
                $entityManager->flush();
                $this->addFlash('success', 'Votre Sortie à été supprimée avec succes!');
                return $this->redirectToRoute('app_home');
            }
    
            $entityManager->persist($sortie);
            $entityManager->flush();
    
            $this->addFlash('success', 'Votre Sortie à été modifié avec succes!');
            return $this->redirectToRoute('app_home');
        }
    
        return $this->render('sortie/modifier.html.twig', [
            'sortieForm' => $sortieForm->createView(),
            'sortie' => $sortie
        ]);
    }
    

    #[Route("/sortie/inscription/{id<[0-9]+>}", name: "app_sortie_inscription")]
    public function inscription (SortieRepository $sortieRepository,$id,
                                 EntityManagerInterface $entityManager): Response
    {
        $user=$this->getUser();
        $sortie =$sortieRepository->find($id);
        $sortie->addUsersInscrit($user);

        if ($sortie->getNbInscriptionsMax() === $sortie->getUsersInscrits()->count()) {
            $sortie->setEtat(EtatEnum::CLOTUREE);
        }
        $entityManager->persist($sortie);
        $entityManager->flush();
        $this->addFlash('success', 'Vous vous etes inscrit avec succés!');
        return $this->redirectToRoute('app_home');
    }


    #[Route("/sortie/publication/{id<[0-9]+>}", name: "app_sortie_publication")]
    public function publication (SortieRepository $sortieRepository,
                                 $id,
                                 EntityManagerInterface $entityManager): Response
    {

        $sortie = $sortieRepository->find($id);
        $sortie->setEtat(EtatEnum::OUVERTE);
        $entityManager->flush();
        $this->addFlash('success', 'Votre sortie à été publiée avec succès!');
        return $this->redirectToRoute('app_home');
    }

    #[Route("/sortie/desister/{id<[0-9]+>}", name: "app_sortie_desister")]
    public function desister (SortieRepository $sortieRepository,$id,EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();
        $sortie = $sortieRepository->find($id);
        $sortie->removeUsersInscrit($user);

        if ($sortie->getNbInscriptionsMax() > $sortie->getUsersInscrits()->count()) {
            $sortie->setEtat(EtatEnum::OUVERTE);
        }
        $entityManager->persist($sortie);
        $entityManager->flush();
        $this->addFlash('success', 'Vous vous êtes désisté avec succès!');
        return $this->redirectToRoute('app_home');
    }

    #[Route("/annuler/sortie/{id<[0-9]+>}", name: "app_sortie_annuler")]
    public function annulerSortie(Request $request,
                                  EntityManagerInterface $entityManager,
                                  SortieRepository $sortieRepository,
    $id): Response
    {
        $sortie =$sortieRepository->find($id);

        $annulerSortieForm = $this->createForm(AnnulationType::class, $sortie);
        $annulerSortieForm->handleRequest($request);

        if ($annulerSortieForm->isSubmitted() && $annulerSortieForm->isValid()) {
            $env = $request->request->get('annuler');
            $annuler = 'annuler';
            $enregistrer = 'enregistrer';

            if ($env == $annuler) {
                return $this->redirectToRoute('app_home');
                // do anything else you need here, like send an email
            }

            $sortie->setEtat(EtatEnum::ANNULEE);
            $entityManager->persist($sortie);
            $entityManager->flush();

            // do anything else you need here, like send an email
            $this->addFlash('success', 'Votre Sortie a été annulée avec succès!');
            return $this->redirectToRoute('app_home');
        }

        return $this->render('sortie/annulerSortie.html.twig', [
            'annulerSortieForm' => $annulerSortieForm->createView(), 'sortie' => $sortie
        ]);
    }
}
