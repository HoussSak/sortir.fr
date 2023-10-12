<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminController extends AbstractController
{
    #[Route('/admin', name: 'app_dashboard')]
    public function index(UserRepository $userRepository): Response
    {
        $users = $userRepository->findAll();
        return $this->render('admin/dashboard.html.twig',compact('users'));
    }

    #[Route('/admin/changestatus/{id<[0-9]+>}', name: 'app_dashboard_changestatus')]
    public function changeStatus(UserRepository $userRepository, User $user, EntityManagerInterface $entityManagee): Response
    {
        if ($user->getActif() == true) {
            $user->setActif(false);
        } else {
            $user->setActif(true);
        }
        $entityManagee->persist($user);
        $entityManagee->flush();
        return $this->redirectToRoute('app_dashboard');
    }

    #[Route('/admin/user/delete/{id<[0-9]+>}', name: 'app_dashboard_delete')]
    public function deleteUser(UserRepository $userRepository, User $user, EntityManagerInterface $entityManager): Response
    {
        $entityManager->remove($user);
        $entityManager->flush();
        return $this->redirectToRoute('app_dashboard');
    }
}
