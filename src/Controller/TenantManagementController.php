<?php

namespace App\Controller;

use App\Form\TenantManagementAddType;
use App\Form\TenantManagementEditType;
use Doctrine\ORM\EntityManagerInterface;
use Faker\Factory;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\User;
use App\Repository\UserRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;

class TenantManagementController extends AbstractController
{
    /**
     * @IsGranted("ROLE_OWNER", statusCode=404, message="Vous ne pouvez pas accéder à cette page avec votre rôle !")
     */
    public function index(UserRepository $userRepo): Response
    {
        $users = $userRepo->findAll();

        return $this->render('tenant_management/tenant_management.html.twig', [
            'users' => $users,
        ]);
    }

    public function add(Request $request, EntityManagerInterface $manager, UserPasswordHasherInterface $passwordHasher, MailerInterface $mailer) :Response
    {
        $faker = Factory::create('fr_FR');
        $user = new User();
        /* pour que sa selectionne automatiquement le rôle "locataire" */
        $user->setRole('ROLE_TENANT');
        $user->setPassword($faker->password());
        $user->setIsVerified(0);
        $user->setToken($this->generateToken());

        $form = $this->createForm(TenantManagementAddType::class, $user);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $email = (new TemplatedEmail())
                ->from('ppe@symfony.fr')
                ->to($user->getEmail())
                ->subject('Confirmation d\'inscription')
                ->htmlTemplate('tenant_management/tenant_management_confirmation.html.twig')
                ->context([
                    'token' => $user->getToken(),
                    'firstname' => $user->getFirstname(),
                    'lastname' => $user->getLastname(),
                    'password' => $user->getPassword(),
                    'Adremail' => $user->getEmail(),
                ])
                ->text('Bonjour, ' . $user->getFirstname() . ' ' . $user->getLastname() . ' Voici vos identifiants : ' . $user->getEmail() . ' ' . $user->getPassword());
            $mailer->send($email);

            $hash = $passwordHasher->hashPassword($user, $user->getPassword());

            $user->setPassword($hash);

            $manager->persist($user);
            $manager->flush();

            return $this->redirectToRoute('login');
        }

        return $this->render('tenant_management/tenant_management_add.html.twig', [
            'form' => $form->createView(),
        ]);
    }


    /**
     * @Route("/confirmer-mon-compte/{token}", name="confirm_account")
     * @param string $token
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function confirmAccount(string $token, EntityManagerInterface $manager, UserRepository $userRepo)
    {
        $user = $userRepo->findOneBy(["token" => $token]);

        if ($user)
        {
            $user->setToken('');
            $user->setIsVerified(1);
            $manager->persist($user);
            $manager->flush();

            return $this->redirectToRoute('login');

        }
    }

    private function generateToken()
    {
        return rtrim(strtr(base64_encode(random_bytes(32)), '+/', '-_'), '=');
    }

    public function edit(User $user = null, Request $request, EntityManagerInterface $manager, UserPasswordHasherInterface $passwordHasher)
    {
        if (!$user) {
            $user = new User();
        }

        $form = $this->createForm(TenantManagementEditType::class, $user);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {

            $hash = $passwordHasher->hashPassword($user, $user->getPassword());

            $user->setPassword($hash);

            $manager->persist($user);
            $manager->flush();


            return $this->redirectToRoute('tenant_management_edit', ['id' => $user->getId()]);
        }

        return $this->render('tenant_management/tenant_management_edit.html.twig', [
            'formUser' => $form->createView(),
            'user' => $user,

        ]);
    }
}
