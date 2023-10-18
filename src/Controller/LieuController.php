<?php

namespace App\Controller;

use App\Entity\Lieu;
use App\Entity\Ville;
use App\Form\LieuType;
use App\Form\VilleType;
use App\Repository\LieuRepository;
use App\Repository\VilleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class LieuController extends AbstractController
{
    #[Route("/admin/lieu", name: "app_lieu")]
    public function index(Request $request,
                          EntityManagerInterface $entityManager,
                          LieuRepository $repo): Response
    {
        $lieux=$repo->findAll();
        $lieu=new Lieu();
        $lieuForm=$this->createForm(LieuType::class,$lieu);
        $lieuForm->handleRequest($request);
        if ($lieuForm->isSubmitted() && $lieuForm->isValid())
        {
            $entityManager->persist($lieu);
            $entityManager->flush();
            return $this->redirectToRoute('app_lieu');
        }

        return $this->render('lieu/index.html.twig',[
            'lieuForm'=>$lieuForm->createView(),'lieux'=>$lieux
        ]);
    }

    #[Route("/admin/supprimerLieu/{id<[0-9]+>}", name: "app_supprimer_lieu")]
    public function supprimer(LieuRepository $repo,Lieu $lieu): Response
    {
        $repo->remove($lieu);
        return $this->redirectToRoute('app_lieu');
    }

    #[Route("/admin/modifierLieu/{id<[0-9]+>}", name: "app_modifier_lieu")]
    public function modifier(EntityManagerInterface $entityManager,Lieu $lieu): Response
    {
        return $this->render('ville/modifier.html.twig');
    }
}
