<?php

namespace App\Controller;

use App\Entity\Conversation;
use App\Entity\Message;
use App\Form\ConversationType;
use App\Form\MessageType;
use App\Repository\ConversationRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Mercure\Authorization;
use Symfony\Component\Mercure\HubInterface;
use Symfony\Component\Mercure\Update;
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
    public function show(Conversation $conversation, Request $request, EntityManagerInterface $em, HubInterface $hub, Authorization $authorization): Response
    {
        $to_id = (array_values(array_diff($conversation->getUser()->toArray(), array($this->getUser())))[0]->getId());
        $message = new Message();
        $form = $this->createForm(MessageType::class, $message);
        $emptyForm = clone $form;
        
        $form->handleRequest($request);
        
        if($form->isSubmitted() && $form->isValid()) {
            $form = $emptyForm;
            
            $message->setConversation($conversation);
            $message->setFromId($this->getUser()->getId());
            $message->setToId($to_id);
            
            $em->persist($message);
            $em->flush();
            
            //hub mercure
            $hub->publish(new Update(
                sprintf(
                    'http://127.0.0.1:8000/conversation/%s',
                    $conversation->getId()
                ),
                // true,
                $this->renderView('message/message.stream.html.twig', ['message' => $message,
                 'form' => $form
                 ] ),
            ));

            // $authorization->setCookie($request, [sprintf(
            //     'http://127.0.0.1:8000/conversation/%s',
            //     $conversation->getId()
            //     )], [],
            //             [], );

            
            
                }
                
    return $this->render('conversation/show.html.twig', ['conversation' => $conversation, 'form' => $form]);
}

    #[Route('/conversation', name: 'app_conversation_all')]
    public function showAll(): Response
    {
        dd($this->conversationRepository->findWithOneUser($this->getUser()));
        // return $this->render('conversation/show.html.twig', ['conversation' => $conversation]);
    }


}
