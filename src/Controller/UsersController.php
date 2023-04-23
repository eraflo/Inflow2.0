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
    #[Route('/inscription', 'home.inscription.index', methods: ['GET', 'POST'])]
    public function inscription(Request $request, EntityManagerInterface $em)
    {
        $user = new Users();
        
        $inscriptionForm = $this->createForm(InscriptionFormType::class, $user);

        $inscriptionForm->handleRequest($request);
        
        if ($inscriptionForm->isSubmitted() && $inscriptionForm->isValid()) {

            $user->setPassword(password_hash($user->getPassword(), PASSWORD_BCRYPT));
            

            $em->persist($user);
            $em->flush();

            return $this->redirectToRoute('home.inscription.index');
        }

        return $this->render('user/inscription.html.twig',  [
            'inscriptionForm' => $inscriptionForm,
        ]);
    }

}