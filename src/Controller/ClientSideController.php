<?php

namespace App\Controller;

use App\Entity\City;
use App\Entity\Lodging;
use App\Repository\CriteriaRepository;
use App\Repository\LodgingRepository;
use App\Repository\RegionRepository;
use App\Repository\UserRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
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


    // #[Route('/all/{page?1}/{nbre?6}', name: 'app_home')]
    // public function indexAll(ManagerRegistry $doctrine, $page, $nbre, UserInterface $user = null, CriteriaRepository $criteriaRepository): Response
    #[Route('/all', name: 'app_home')]
    public function indexAll(LodgingRepository $lodgingRepository, UserInterface $user = null, CriteriaRepository $criteriaRepository, RegionRepository $regionRepository, Request $request): Response
    {
        $favs = '';

        if ($this->isGranted('IS_AUTHENTICATED_FULLY')) {
            $favs = $user->getFavs();
        }
        
        // On définit le nombre d'élement par page
        $limit = 6;

        //on récupère le numéro de page
        $page = (int)$request->query->get('page', 1);

        // on récupère les filtres 
        $filters['criterion'] = $request->get('criterion');
        $filters['rooms'] = $request->get('rooms');
        $filters['price']['low'] = $request->get('lowPrice');
        $filters['price']['high'] = $request->get('highPrice');
        $filters['region'] = $request->get('region');
        $filters['city']['zip_code'] = $request->get('cityZipCode');
        $filters['city']['name'] = $request->get('cityName');
        $filters['city']['zone'] = $request->get('zone');
        $filters['city']['GpsLat'] = $request->get('cityGpsLat');
        $filters['city']['GpsLng'] = $request->get('cityGpsLng');
        

        // on récupère les lodgings
        
        $lodgings = $lodgingRepository->getPaginatedLodgings($page, $limit, $filters);

        // on récupere le nombre total de gîtes
         // --- pagination ---
        //  dd($lodgings);
        $nbLodging = count($lodgingRepository->getTotalLodgings($filters));
        
         $nbrePage = ceil($nbLodging / $limit);  
        // $lodgings = $lodgingRepository->findByCriteria($filters);
        

        $isPaginated = true;

       

        if ($nbLodging < 7) {
            $isPaginated = false;
        }

        if ($request->get('ajax')) {
            return new JsonResponse([
                'content' => $this->renderView('client_side/_content.html.twig', [
                    'isPaginated' => $isPaginated,
                    'nbrePage' => $nbrePage,
                    'page' => $page,
                    'favs' => $favs,
                    'nbre' => $limit,
                    'lodgs' => $lodgings,
                    'filters' => $filters
                    ])
            ]);
        }
        

        $criterion = $criteriaRepository->findAll();
        $regions = $regionRepository->findAll();

        return $this->render('client_side/index.html.twig', [
            'isPaginated' => $isPaginated,
            'nbrePage' => $nbrePage,
            'page' => $page,
            'lodgs' => $lodgings,
            'favs' => $favs,
            'criterion' => $criterion,
            'regions' => $regions
        ]);
        
    }


    #[Route('/map/all', name: 'map_all')]
    public function mapAll(LodgingRepository $lodgingRepository, CriteriaRepository $criteriaRepository, RegionRepository $regionRepository, Request $request): Response
    {
        $filters['criterion'] = $request->get('criterion');
        $filters['rooms'] = $request->get('rooms');
        $filters['price']['low'] = $request->get('lowPrice');
        $filters['price']['high'] = $request->get('highPrice');
        $filters['region'] = $request->get('region');
        $filters['city']['zip_code'] = $request->get('cityZipCode');
        $filters['city']['name'] = $request->get('cityName');
        $filters['city']['zone'] = $request->get('zone');
        $filters['city']['GpsLat'] = $request->get('cityGpsLat');
        $filters['city']['GpsLng'] = $request->get('cityGpsLng');

        $lodgings = $lodgingRepository->getMappedLodgings($filters);

        if ($request->get('ajax')) {
            return new JsonResponse([
                'content' => $this->renderView('client_side/_mapContent.html.twig', [
                    
                    'lodgings' => $lodgings
                    ])
            ]);
        }
        
        $criterion = $criteriaRepository->findAll();
        $regions = $regionRepository->findAll();

        return $this->render('client_side/showall.html.twig', [
                    'criterion' => $criterion,
                    'regions' => $regions,
                'lodgings' =>  $lodgings
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
