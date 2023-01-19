<?php

namespace App\Controller;

use App\Entity\Lodging;
use App\Entity\User;
use App\Repository\LodgingRepository;
use App\Repository\UserRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;

#[Route('/favs')]
class FavsController extends AbstractController
{

    #[Route('/index', name: 'app_favs_index')]
    public function index(UserInterface $userInterface, ManagerRegistry $doctrine): Response
    {

        $repository = $doctrine->getRepository(Lodging::class);
        $lodgings = $repository->findAll();

        return $this->render('favs/index.html.twig', [
            'lodgs' => $lodgings,
            'favs' => $userInterface->getFavs()
        ]);
    }

    #[Route('/add/{id}', name: 'app_favs_add')]
    public function addFav(Lodging $lodging = null, UserInterface $user, ManagerRegistry $doctrine): Response
    {
        if (!$lodging || !$lodging->getCity()) {
            $this->addFlash('error', 'Le gîte n\'existe pas');
            return $this->redirectToRoute('app_home');
        }
        
        $favs[] = $lodging->getId();
        $user->setFavs($favs);

        $manager = $doctrine->getManager();
        $manager->persist($user);
        $manager->flush();

        return $this->redirectToRoute('app_home');
        
    }

    public function removeFromFavs()
    {
        # code...
    }
}
