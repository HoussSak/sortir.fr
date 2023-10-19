<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\ModifierProfilFormType;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
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

    #[Route("/admin/user/edit/{id<[0-9]+>}", name:"app_dashboard_user_edit")]
    public function edit(Request $request,
                         UserPasswordHasherInterface $userPasswordHasher,
                         UserRepository $userRepository,
                         $id,
                         EntityManagerInterface $entityManager): Response
    {
        $user = $userRepository->find($id);
        $admin =$this->getUser();
        $form = $this->createForm(ModifierProfilFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $plainPassword = $form->get('plainPassword')->getData();

            if ($userPasswordHasher->isPasswordValid($admin, $plainPassword)) {
                $entityManager->persist($user);
                $entityManager->flush();
                // do anything else you need here, like send an email
                $this->addFlash('success', "Le compte de l'utilisateur " . $user->getNom() . " a été modifié avec succès!");
                return $this->redirectToRoute('app_dashboard');

            } else {
                $this->addFlash('danger', 'Veuillez saisir votre mot de passe actuel');
                return $this->redirectToRoute('app_dashboard_user_edit', ['id' => $user->getId()]);

            }

        }

        return $this->render('admin/edit_user.html.twig', [
            'registrationForm' => $form->createView(),
            'user' => $user
        ]);
    }
}
