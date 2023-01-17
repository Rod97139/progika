<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class OwnerSideController extends AbstractController
{
    #[Route('/owner/side', name: 'app_owner_side')]
    public function index(): Response
    {
        return $this->render('owner_side/index.html.twig', [
            'controller_name' => 'OwnerSideController',
        ]);
    }
}
