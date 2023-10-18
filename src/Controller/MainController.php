<?php



namespace App\Controller;



use App\Entity\User;

use App\Form\FiltreSortieType;

use App\Repository\SiteRepository;

use App\Repository\SortieRepository;

use App\utils\EtatEnum;
use Doctrine\ORM\EntityManagerInterface;
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

        Request $request,
        EntityManagerInterface $entityManager

    ): Response {

        $sortiesList = $sortieRepository->findAll();
        $today = new \DateTime();
        foreach ($sortiesList as $sortie) {
        if ($sortie->getDateDebut() !== null && $sortie->getDateDebut()->format('Y-m-d') === $today->format('Y-m-d')) {
        $sortie->setEtat(EtatEnum::EN_COURS);
        $entityManager->persist($sortie);
        $entityManager->flush();
         }
        elseif($sortie->getDateDebut() !== null && $sortie->getDateDebut()->format('Y-m-d') < $today->format('Y-m-d')) {
            $sortie->setEtat(EtatEnum::PASSEE);
            $entityManager->persist($sortie);
            $entityManager->flush();
        }
        elseif($sortie->getDateCloture() !== null &&
            $sortie->getDateCloture()->format('Y-m-d') < $today->format('Y-m-d') &&
            $sortie->getEtat() == EtatEnum::OUVERTE
        ) {
            $sortie->setEtat(EtatEnum::CLOTUREE);
            $entityManager->persist($sortie);
            $entityManager->flush();
        }
       if ($sortie->getDateDebut() !== null &&
                $sortie->getDateDebut()->diff($today)->m >= 1) {
                $sortie->setEtat(EtatEnum::ARCHIVEE);
                $entityManager->persist($sortie);
                $entityManager->flush();
       }

        }

        $user = $this->getUser();
        if (!$user instanceof User) {
            return $this->redirectToRoute('app_login');
        }
        $sites = $siteRepository->findAll();
        $userSite = $user->getSite();
    
        $filtreForm = $this->createForm(FiltreSortieType::class);
        $filtreForm->handleRequest($request);

        $sorties = $sortieRepository->findAllWithoutArchivee();

        return $this->render('main/index.html.twig', [
            'sites' => $sites,
            'sorties' => $sorties,
            'filtreForm' => $filtreForm->createView(),
            'userSite' => $userSite,
        ]);
    }

}
