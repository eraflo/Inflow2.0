<?php

namespace App\Controller;

use App\Entity\Users;
use App\Form\InscriptionFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class RegistrationController extends AbstractController
{
    #[Route('/inscription', name: 'app_registration', methods: ['GET', 'POST'])]
    public function index(UserPasswordHasherInterface $passwordHasher, Request $request, EntityManagerInterface $em)
    {
        if ($this->getUser()) {
            return $this->redirectToRoute('app_home');
        }
        // ... e.g. get the user data from a registration form
        $user = new Users();
        
        $inscriptionForm = $this->createForm(InscriptionFormType::class, $user);

        $inscriptionForm->handleRequest($request);
        
        if ($inscriptionForm->isSubmitted() && $inscriptionForm->isValid()) {

            // hash the password (based on the security.yaml config for the $user class)
            $plaintextPassword = $inscriptionForm->get('password')->getData();
            $hashedPassword = $passwordHasher->hashPassword(
                $user,
                $plaintextPassword
            );
            $user->setPassword($hashedPassword);


            $em->persist($user);
            $em->flush();

            return $this->redirectToRoute('app_home');
        }

        return $this->render('users/inscription.html.twig',  [
            'inscriptionForm' => $inscriptionForm,
        ]);

    }
}
