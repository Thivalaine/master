<?php

namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\User;
use App\Form\RegistrationType;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

class RegistrationController extends AbstractController
{
    public function index(): Response
    {
        return $this->render('registration/index.html.twig', [
            'controller_name' => 'RegistrationController',
        ]);
    }

    /**
     * @IsGranted("ROLE_OWNER", statusCode=404, message="Vous ne pouvez pas accéder à cette page avec votre rôle !")
     */

    public function registration(Request $request, EntityManagerInterface $manager, UserPasswordHasherInterface $passwordHasher, MailerInterface $mailer)
    {
        $user = new User();
        $form = $this->createForm(RegistrationType::class, $user);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $hash = $passwordHasher->hashPassword($user, $user->getPassword());

            $user->setPassword($hash);

            $email = (new Email())
                ->from('ppe@symfony.fr')
                ->to($user->getEmail())
                ->subject('Confirmation d\'inscription')
                ->text('Bonjour, ' . $user->getFirstname() . ' ' . $user->getLastname() . ' Voici vos identifiants : ' . $user->getEmail() . ' ' . $user->getPassword());
            $mailer->send($email);

            $manager->persist($user);
            $manager->flush();

            return $this->redirectToRoute('login');
        }

        return $this->render('registration/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
