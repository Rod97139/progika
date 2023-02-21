<?php

namespace App\Controller;

use App\Entity\Conversation;
use App\Form\ConversationType;
use App\Repository\ConversationRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;
use Symfony\Component\Security\Csrf\TokenGenerator\TokenGeneratorInterface;

class ConversationController extends AbstractController
{

    public function __construct(private UserRepository $userRepository, private ConversationRepository $conversationRepository, private TokenGeneratorInterface $tokenGenerator, private CsrfTokenManagerInterface $tokenmanager) {}

    #[Route('/conversation/new/{ownerId}/{lodgingId}', name: 'app_conversation')]
    public function new($ownerId, $lodgingId, EntityManagerInterface $manager, SessionInterface $session): Response
    {
        // // Générer un nouveau token CSRF
        // $token = $this->tokenGenerator->generateToken();
        // // Enregistrer le token dans la session pour une utilisation future
        // $session->set('csrf_token', $token);
        $client = $this->getUser();

        $test = $this->conversationRepository->findBy(['user_id' => $ownerId, 'user_id' => $client->getId()]);
        dd($test);
        $owner = $this->userRepository->findBy(['id' => $ownerId]);
        $conversation = new Conversation();
        $conversation->addUser($client)
                    ->addUser($owner[0])
                    ;
        $conversation->setLodgingId($lodgingId);
        $manager->persist($conversation);
        $manager->flush();
        // dd($conversation);

        return $this->render('conversation/new.html.twig');
    }
}
