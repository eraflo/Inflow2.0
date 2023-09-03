<?php
namespace App\Controller;

use App\Entity\Users;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\InscriptionFormType;

class UsersController extends AbstractController
{

    #[Route('users/{id}', name: 'app_users_show', methods: ['GET'])]
    public function show($id, EntityManagerInterface $em) {
        $user = $em->getRepository(Users::class)->find($id);
        return $this->render('users/profile.html.twig', [
            'user' => $user,
        ]);
    }

}