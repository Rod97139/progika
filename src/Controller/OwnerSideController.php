<?php

namespace App\Controller;

use App\Form\UserType;
use App\Repository\LodgingRepository;
use App\Repository\UserRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;

class OwnerSideController extends AbstractController
{
    #[Route('/owner', name: 'app_owner_side')]
    public function index(): Response
    {
        return $this->render('owner_side/index.html.twig', [
            'controller_name' => 'OwnerSideController',
        ]);
    }

    #[Route('/{id}/edit', name: 'app_owner_edit', methods: ['GET', 'POST'])]
    public function editProfile(Request $request, UserInterface $user, UserRepository $userRepository): Response
    {
        $form = $this->createForm(UserType::class, $user)
                    ->remove('updated_at')
                    ->remove('created_at')
                    ->remove('isVerified')
                    ->handleRequest($request);
        $user->setUpdatedAt(new \DateTime());

        if ($form->isSubmitted() && $form->isValid()) {
            $userRepository->save($user, true);

            return $this->redirectToRoute('app_home', [], Response::HTTP_SEE_OTHER);
        }
        return $this->render('admin/edit.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }



    #[Route('/owner/lodging', name: 'app_owner_lodging_list', methods: ['GET'])]
    public function indexLodgings(UserInterface $user, LodgingRepository $lodgingRepository): Response
    {
            return $this->render('lodging/index.html.twig', [
                'lodgings' => $lodgingRepository->findBy(['user' => $user->getId()]),
            ]);
    }

    #[Route('/owner/make', name: 'app_make_owner')]
    public function makeOwner(UserInterface $user, ManagerRegistry $doctrine): Response
    {


        $user->setRoles(['ROLE_OWNER']);


        $manager = $doctrine->getManager();
            $manager->persist($user);
            $manager->flush();



        return $this->redirectToRoute('app_login');
    }


}
