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

    public function __construct(private UserRepository $userRepository, private ConversationRepository $conversationRepository) {}

    #[Route('/conversation/new/{ownerId}/{lodgingId}', name: 'app_conversation_new')]
    public function newContact($ownerId, $lodgingId, EntityManagerInterface $manager): Response
    {
        $create = true;
        $client = $this->getUser();
        $owner = $this->userRepository->findOneBy(['id' => $ownerId]);
        $conversations = $this->conversationRepository->findByUsers($client, $owner);

        foreach ($conversations as $conv) {
            if ($conv->getLodgingId() === (int)$lodgingId) {
               $create = false;
               $conversation = $conv;
            }
        }
        
        if($create){
            $conversation = new Conversation();
            $conversation->addUser($client)
                        ->addUser($owner);
            $conversation->setLodgingId($lodgingId);
            $manager->persist($conversation);
            $manager->flush();

            // dd($conversation);

        }
        
        // dd(($this->conversationRepository->findByUsers($client, $owner)));
        return $this->redirectToRoute('app_conversation', ['id' => $conversation->getId()]);

    }

    #[Route('/conversation/{id}', name: 'app_conversation')]
    public function show(Conversation $conversation): Response
    {
        
        return $this->render('conversation/show.html.twig', ['conversation' => $conversation]);
    }

    #[Route('/conversation', name: 'app_conversation_all')]
    public function showAll(): Response
    {
        dd($this->conversationRepository->findWithOneUser($this->getUser()));
        // return $this->render('conversation/show.html.twig', ['conversation' => $conversation]);
    }


}
