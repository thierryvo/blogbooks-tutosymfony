<?php

namespace App\Controller;

use App\Form\ContactType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mailer\MailerInterface;

// Class ContactController (public: contact) 
class ContactController extends AbstractController
{
    // Action index accessible par la route /contact --------------------------------- index
    // index = 
    /**
     * @Route("/contact", name="app_contact")
     */
    public function index(Request $request, MailerInterface $mailer): Response
    {
        $form = $this->createForm(ContactType::class);      // create Formulaire: ContactType
        $form->handleRequest($request);                     // récupérer data du Formulaire
        // FORMULAIRE Soumis & prêt à être traité
        if ($form->isSubmitted() && $form->isValid()) {
            // CONSTRUIRE le mail avec les données récupérées
            $email = $form->get('email')->getData(); // récupère email
            $sujet = $form->get('sujet')->getData(); // récupère sujet
            $contenu = $form->get('contenu')->getData(); // récupère contenu

            // EMAIL
            $adminDuSite = "thi.voz@gmail.com";       // admin du site à renseigner
            $oObjet_email = (new Email())
                ->from($email)
                ->to($adminDuSite)
                //->cc('cc@example.com')              personne en copie
                //->bcc('bcc@example.com')            personne en copie cachée
                //->replyTo('fabien@example.com')     SI reponse: répondre à qui
                //->priority(Email::PRIORITY_HIGH)    priorité: BLEU VERT ROUGE
                ->subject($sujet)
                ->text($contenu);
                // ->html('<p>See Twig integration for better HTML integration!</p>');    contenu en html    
                
            return $this->redirectToRoute('app_merci'); // redirection vers: Merci
            // ENVOIE EMAIL
            $mailer->send($oObjet_email);     
            // ici normalement 
        }

        // render rendre la PAGE-FORMULAIRE  contact / index
        return $this->renderForm(
            'contact/index.html.twig', 
            [
                'controller_name' => 'ContactController',
                'form' => $form,
            ]
        );
    }

    // Action merci accessible par la route /contact/merci ---------------------------- merci
    // index = 
    /**
     * @Route("/contact/merci", name="app_merci")
     */
    public function merci(): Response
    {
        // render rendre la PAGE  merci / index
        return $this->render(
            'merci/index.html.twig', 
            ['controller_name' => 'MerciController',]
        );
    }    








}
