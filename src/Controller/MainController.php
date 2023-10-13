<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\FiltreSortieType;
use App\Repository\SiteRepository;
use App\Repository\SortieRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
    #[Route('/home', name: 'app_home')]
    public function home(
        SiteRepository $siteRepository,
        SortieRepository $sortieRepository,
        Request $request
    ): Response {
        $user = $this->getUser();
        if (!$user instanceof User) {
            return $this->redirectToRoute('app_login');
        }

        $sites = $siteRepository->findAll();
        $userSite = $user->getSite();

        $filtreForm = $this->createForm(FiltreSortieType::class);
        $filtreForm->handleRequest($request);

        $choixSite = $request->request->get('site');

        // By default, display Sorties from the user's site
        $sorties = $sortieRepository->findByExampleField($userSite->getId());

        if ($filtreForm->isSubmitted()) {
            if ($choixSite) {
                // Set the selected site to the session
                $request->getSession()->set('selected_site_id', $choixSite);

                $sorties = $sortieRepository->findByExampleField($choixSite);
            }
        }

        return $this->render('main/index.html.twig', [
            'sites' => $sites,
            'sorties' => $sorties,
            'filtreForm' => $filtreForm->createView(),
            'userSite' => $userSite,
        ]);
    }

}