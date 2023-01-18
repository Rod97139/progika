<?php

namespace App\Controller;

use App\Entity\Lodging;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/')]
class ClientSideController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(ManagerRegistry $doctrine): Response
    {

        $repository = $doctrine->getRepository(Lodging::class);
        $lodgs = $repository->findAll();

        return $this->render('client_side/index.html.twig', [
            'lodgs' => $lodgs,
        ]);
    }
}
