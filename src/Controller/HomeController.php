<?php

namespace App\Controller;

use App\Form\ContactType;
use App\Repository\ProjectRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Mime\Email;

/**
 * Class HomeController
 * @package App\Controller
 */
class HomeController extends AbstractController
{

    /**
     *@Route("/", name="home")
     */
    public function index(Request $request, ProjectRepository $projectRepository, MailerInterface $mailer)
    {
        $form = $this->createForm(ContactType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();


            return $this->redirectToRoute('home');
        }

        $email = (new Email())
            ->from('your_email@example.com')
            ->to('your_email@example.com')
            ->subject('Un nouveau message vient d\'arriver !')
            ->html('<p>Un nouveau message vient d\'arriver !</p>');


        $mailer->send($email);

        return $this->render("home/index.html.twig", [
            'form' => $form->createView(),
            'projects' => $projectRepository->findAll(),
        ]);
    }
}