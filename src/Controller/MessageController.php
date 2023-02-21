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
        $message = new Message();
        $form = $this->createForm(MessageType::class, $message);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {
            $message->setConversation($conversation);
            $message->setFromId($this->getUser()->getId());
            $em->persist($message);
            $em->flush();
            dd($message);
        }

        return $this->render('message/new.html.twig', [
            'conversation' => $conversation,
            'form' => $form
        ]);
    }
}
