<?php

namespace App\Controller;

use App\Entity\Criteria;
use App\Repository\CriteriaRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/home')]
    public function index(ManagerRegistry $doctrine): Response
    {
        $repository = $doctrine->getRepository(Criteria::class);
        $criterion = $repository->findBy([], ["type" => "DESC"]);


        return $this->render('home/index.html.twig', [
            'criterion' => $criterion,
        ]);
    }
}
