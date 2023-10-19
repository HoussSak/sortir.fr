<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use Doctrine\ORM\EntityManagerInterface;
use League\Csv\Reader;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

class RegistrationController extends AbstractController
{
    #[Route('/admin/register', name: 'app_register')]
    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager): Response
    {
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // encode the plain password
            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );

            if($form->get('administrateur')->getData() === true) {
                $user->setRoles(['ROLE_ADMIN']);
            } else {
                $user->setRoles(['ROLE_USER']);
            }

            $entityManager->persist($user);
            $entityManager->flush();
            // do anything else you need here, like send an email
            $this->addFlash('success', "le compte de l'utilisateur " . $user->getNom() ." a été créee avec succès!");
            return $this->redirectToRoute('app_dashboard');
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }


    #[Route('/admin/create_users_from_csv', name: 'create_users_from_csv')]
    public function createUsersFromCSV(UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager): Response
    {
        $csvFilePath = '/../../public/csvs/users.csv';
        $csv = Reader::createFromPath($csvFilePath, 'r');
        $csv->setHeaderOffset(0);

        $records = $csv->getRecords();

        foreach ($records as $record) {
            $user = new User();
            $user->setEmail($record['email']);
            $user->setRoles([$record['roles']]);

            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $record['password']
                )
            );

            $user->setNom($record['nom']);
            $user->setPrenom($record['prenom']);
            $user->setAdministrateur($record['administrateur'] === 'false');
            $user->setActif($record['actif'] === 'true');
            $user->setPhoto($record['photo']);
            $user->setPseudo($record['pseudo']);
            $user->setTelephone($record['telephone']);
            $entityManager->persist($user);
        }

        $entityManager->flush();
        $this->addFlash('success', "Les utilisateurs ont été créés avec succès depuis le fichier CSV.");
        return $this->redirectToRoute('app_dashboard');
    }




}