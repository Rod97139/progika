<?php

namespace App\Controller;

use App\Form\UserType;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;

class OwnerSideController extends AbstractController
{
    #[Route('/owner/side', name: 'app_owner_side')]
    public function index(): Response
    {
        return $this->render('owner_side/index.html.twig', [
            'controller_name' => 'OwnerSideController',
        ]);
    }

    #[Route('/{id}/edit', name: 'app_owner_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, UserInterface $user, UserRepository $userRepository): Response
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

    public function editCottage()
    {
        
    }
}
