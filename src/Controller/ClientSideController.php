<?php

namespace App\Controller;

use App\Entity\City;
use App\Entity\Lodging;
use App\Repository\LodgingRepository;
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
        $lodgs = $repository->findBy([], ["id" => "DESC"]);

        return $this->render('client_side/index.html.twig', [
            'lodgs' => $lodgs,
        ]);
    }

    #[Route('/all')]
    public function mapAll(LodgingRepository $lodgingRepository): Response
    {
       

        return $this->render('client_side/showall.html.twig', [
                'lodgings' =>  $lodgingRepository->findAll()
        ]);
    }

    #[Route('/detail/{id<\d+>}', name: 'app_client_detail')]
    public function detail(Lodging $lodging = null): Response
    {

        if (!$lodging || !$lodging->getCity()) {
            $this->addFlash('error', 'Le gîte n\'existe pas');
            return $this->redirectToRoute('app_home');
        }

        $coordgps[] = $lodging->getCity()->getGpsLat();
        $coordgps[] = $lodging->getCity()->getGpsLng();

        

        return $this->render('client_side/detail.html.twig', [
                'lodging' => $lodging,
                'gps' => $coordgps
            ]);
    }
}
