<?php

namespace App\Controller;

use App\Entity\Conversation;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MessageController extends AbstractController
{
    #[Route('/conversation/{conversation}/message/new', name: 'app_message_new')]
    public function new(Conversation $conversation): Response
    {
        return $this->render('message/new.html.twig', [
            'conversation' => $conversation
        ]);
    }
}
