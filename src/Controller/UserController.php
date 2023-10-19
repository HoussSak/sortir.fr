<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\ModifierProfilFormType;
use App\Form\RegistrationFormType;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{

    #[Route("/user/edit", name:"app_user_edit")]
    public function edit(Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager): Response
    {
        $user =$this->getUser();
        $form = $this->createForm(ModifierProfilFormType::class, $user);
        $form->handleRequest($request);


        if ($form->isSubmitted()) {
            $plainPassword = $form->get('plainPassword')->getData();

            try {
                if ($userPasswordHasher->isPasswordValid($user, $plainPassword)) {
                    $entityManager->persist($user);
                    $entityManager->flush();
                    $this->addFlash('success', 'Votre compte a été modifié avec succès!');
                    return $this->redirectToRoute('app_home');
                } else {
                    $this->addFlash('danger', 'Veuillez saisir votre mot de passe actuel');
                    return $this->redirectToRoute('app_user_edit');
                }
            } catch (UniqueConstraintViolationException $e) {
                $this->addFlash('danger', 'Le pseudo existe déjà, veuillez en choisir un autre.');
            }

        }

        return $this->render('user/index.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }

    #[Route("/user/afficher/{id<[0-9]+>}", name:"app_user_afficher")]
    public function afficher(User $user): Response
    {

        return $this->render('user/afficher.html.twig',compact('user'));
    }
}


