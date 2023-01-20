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
use Symfony\Component\Security\Http\Attribute\IsGranted;

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
    public function addFav(Lodging $lodging = null, UserInterface $user, ManagerRegistry $doctrine, Request $request): Response
    {
        if (!$lodging || !$lodging->getId()) {
            $this->addFlash('error', 'Le gîte n\'existe pas');
            return $this->redirectToRoute('app_home');
        }
        
        $favs[] = $lodging->getId();
        $getFavs = $user->getFavs();
        $getFavs[]=$favs;
        $user->setFavs($getFavs);

        $manager = $doctrine->getManager();
        $manager->persist($user);
        $manager->flush();

        
        $route = $request->headers->get('referer');

        return $this->redirect($route);
        
    }

    #[Route('/remove/{id}', name: 'app_favs_remove')]
    public function removeFromFavs(Lodging $lodging = null, UserInterface $user, ManagerRegistry $doctrine, Request $request): Response
    {
        if (!$lodging || !$lodging->getId()) {
            $this->addFlash('error', 'Le gîte n\'existe pas');
            return $this->redirectToRoute('app_home');
        }
        
        $favs[] = $lodging->getId();
        $getFavs = $user->getFavs();


        $i = 0;
        dump($getFavs);
        foreach ($getFavs as $value) {
            if ($value === $favs) {
                unset($getFavs[$i]);
            }
            $i = $i+1;
        }

        $getFavs = array_values($getFavs);
        $user->setFavs($getFavs);
        $manager = $doctrine->getManager();
        $manager->persist($user);
        $manager->flush();

        $route = $request->headers->get('referer');

        return $this->redirect($route);
        
    }
}
