<?php

namespace App\Controller;

use App\Entity\Conversation;
use App\Entity\Message;
use App\Form\MessageType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MessageController extends AbstractController
{
    #[Route('/conversation/{conversation}/message/new', name: 'app_message_new')]
    public function new(Conversation $conversation, Request $request, EntityManagerInterface $em): Response
    {
        $to_id = (array_values(array_diff($conversation->getUser()->toArray(), array($this->getUser())))[0]->getId());
        $message = new Message();
        $form = $this->createForm(MessageType::class, $message, [
            'action' =>$this->generateUrl('app_message_new', ['conversation' => $conversation->getId()])
        ]);
        $form->remove('createdAt')->remove('updatedAt')->remove('readAt')->remove('from_id')->remove('to_id');
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $message->setConversation($conversation);
            $message->setFromId($this->getUser()->getId());
            $message->setToId($to_id);
            
            $em->persist($message);
            $em->flush();

            return $this->redirectToRoute('app_conversation', ['id' => $conversation->getId()]);
        }

        return $this->render('message/new.html.twig', [
            'conversation' => $conversation,
            'form' => $form
        ]);
    }
}
