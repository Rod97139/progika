<?php

namespace App\Controller;

use App\Entity\Conversation;
use App\Entity\Message;
use App\Form\MessageType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mercure\Authorization;
use Symfony\Component\Mercure\HubInterface;
use Symfony\Component\Mercure\Update;
use Symfony\Component\Routing\Annotation\Route;

class MessageController extends AbstractController
{
    // #[Route('/conversation/{conversation}/message/new', name: 'app_message_new')]
    // public function new(Conversation $conversation, Request $request, EntityManagerInterface $em, HubInterface $hub, Authorization $authorization): Response
    // {
    //     $to_id = (array_values(array_diff($conversation->getUser()->toArray(), array($this->getUser())))[0]->getId());
    //     $message = new Message();
    //     $form = $this->createForm(MessageType::class, $message, [
    //         'action' =>$this->generateUrl('app_message_new', ['conversation' => $conversation->getId()])
    //     ]);
    //     $form->remove('createdAt')->remove('updatedAt')->remove('readAt')->remove('from_id')->remove('to_id');
    //     $form->handleRequest($request);

    //     if($form->isSubmitted() && $form->isValid()) {
    //         $message->setConversation($conversation);
    //         $message->setFromId($this->getUser()->getId());
    //         $message->setToId($to_id);
            
    //         $em->persist($message);
    //         $em->flush();
    //         // dd($message);
    //         //hub mercure
    //         $hub->publish(new Update(
    //             sprintf(
    //                 'http://127.0.0.1:8000/conversation/%s',
    //                 $conversation->getId()
    //             ),
    //             // true,
    //             $this->renderView('message/message.stream.html.twig', ['message' => $message]),
    //         ));

            
    //         return $this->redirectToRoute('app_conversation', ['id' => $conversation->getId()]);
    //     }
        
    //     // $authorization->setCookie($request, [sprintf(
    //     //     'http://127.0.0.1:8000/conversation/%s',
    //     //     $conversation->getId()
    //     //     )], [sprintf(
    //     //         'http://127.0.0.1:8000/conversation/%s',
    //     //         $conversation->getId()
    //     //         )]);
        

    //     return $this->render('message/new.html.twig', [
    //         'conversation' => $conversation,
    //         'form' => $form
    //     ]);
    // }
}
