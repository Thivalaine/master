<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class MailController extends AbstractController
{
    #[Route('/mail', name: 'mail')]
    public function index(MailerInterface $mailer): Response
    {
        $email = (new Email())
            ->from('caca@caca.fr')
            ->to('caca@caca.fr')
            ->subject('Test de Mail')
            ->text('Ceci est un mail de test');
        $mailer->send($email);
        return $this->render('mail/index.html.twig', [
            'controller_name' => 'MailController',
        ]);
    }
}
