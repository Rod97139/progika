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
    public function new($ownerId, $lodgingId, EntityManagerInterface $manager): Response
    {
        $create = true;
        $client = $this->getUser();
        $owner = $this->userRepository->findOneBy(['id' => $ownerId]);
        $conversations = $this->conversationRepository->findByUsers($client, $owner);

        foreach ($conversations as $conv) {
            if ($conv->getLodgingId() === (int)$lodgingId) {
               $create = false;
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

            return $this->redirectToRoute('app_conversation', ['id' => $conversation->getId()]);
        }

        dd(($this->conversationRepository->findByUsers($client, $owner)));

    }

    #[Route('/conversation/{id}', name: 'app_conversation')]
    public function show(Conversation $conversation): Response
    {
        
        dd($conversation);
        return $this->render('conversation/show.html.twig', ['conversation' => $conversation]);
    }
}
