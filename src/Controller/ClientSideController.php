<?php

namespace App\Controller;

use App\Entity\City;
use App\Entity\Lodging;
use App\Repository\LodgingRepository;
use App\Repository\UserRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;

#[Route('/')]
class ClientSideController extends AbstractController
{
    #[Route('/', name: 'app_start')]
    public function index(ManagerRegistry $doctrine): Response
    {

        return $this->redirectToRoute('app_home');
        // $repository = $doctrine->getRepository(Lodging::class);
        // $lodgs = $repository->findBy([], ["id" => "DESC"]);

        // return $this->render('client_side/index.html.twig', [
        //     'lodgs' => $lodgs,
        // ]);
    }


    #[Route('/all/{page?1}/{nbre?6}', name: 'app_home')]
    public function indexAll(ManagerRegistry $doctrine, $page, $nbre, UserInterface $user = null): Response
    {
        $favs = '';

        if ($this->isGranted('IS_AUTHENTICATED_FULLY')) {
            $favs = $user->getFavs();
        }
        
        $repository = $doctrine->getRepository(Lodging::class);
        $nbLodging = $repository->count([]);
        
        $nbrePage = ceil($nbLodging / $nbre);
        $lodgings = $repository->findBy([], [], $nbre, ($page - 1) * $nbre );

        return $this->render('client_side/index.html.twig', [
            'lodgs' => $lodgings,
            'isPaginated' => true,
            'nbrePage' => $nbrePage,
            'page' => $page,
            'nbre' => $nbre,
            'favs' => $favs
        ]);
        
    }


    #[Route('/map/all', name: 'map_all')]
    public function mapAll(LodgingRepository $lodgingRepository): Response
    {
       

        return $this->render('client_side/showall.html.twig', [
                'lodgings' =>  $lodgingRepository->findAll()
        ]);
    }

    #[Route('/detail/{id<\d+>}', name: 'app_client_detail')]
    public function detail(Lodging $lodging = null, UserRepository $userRepository): Response
    {

        if (!$lodging || !$lodging->getCity()) {
            $this->addFlash('error', 'Le gîte n\'existe pas');
            return $this->redirectToRoute('app_home');
        }

        $coordgps[] = $lodging->getCity()->getGpsLat();
        $coordgps[] = $lodging->getCity()->getGpsLng();
        $criterion = $lodging->getCriteria();
        $userId = $lodging->getUser();

        return $this->render('client_side/detail.html.twig', [
                'lodging' => $lodging,
                'gps' => $coordgps,
                'criterion' => $criterion,
                'user' => $userRepository->findOneBy(['id' => $userId])
            ]);
    }
}
