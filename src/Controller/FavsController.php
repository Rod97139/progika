<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FavsController extends AbstractController
{
    #[Route('/favs', name: 'app_favs')]
    public function index(): Response
    {
        return $this->render('favs/index.html.twig', [
            'controller_name' => 'FavsController',
        ]);
    }
    public function addFav()
    {
        # code...
    }

    public function removeFromFavs()
    {
        # code...
    }
}
