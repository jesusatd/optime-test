<?php

namespace App\Controller;

use App\Entity\Mail;
use App\Form\MailFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
/**
 * @Route("/contact")
 */
class MailController extends AbstractController
{

    /**
     * @Route("/", name="contact.send", methods={"GET","POST"})
     */
    public function send(Request $request, MailerInterface $mailer): Response
    {
        $mail = new Mail();
        $mail-> setCreatedAt(new \DateTime());

        $form = $this->createForm(MailFormType::class, $mail);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $manager = $this->getDoctrine()->getManager();
            $manager->persist($mail);
            $manager->flush();
            
            $email = (new Email())
            ->from($mail->getEmail())
            ->to('info@contact.com')
            ->subject($mail->getSubject())
            ->text($mail->getMessage());
            $mailer->send($email);

            $this->addFlash(
                'success',
                'Your email has been sent!'
            );

            return $this->redirectToRoute('contact.send');
        }

        return $this->render('mail/index.html.twig', [
            'mail' => $mail,
            'form' => $form->createView(),
        ]);
    }
}
