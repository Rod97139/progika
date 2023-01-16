<?php

namespace App\Controller;

use App\Entity\Criteria;
use App\Entity\Lodging;
use App\Form\LodgingType;
use App\Repository\LodgingRepository;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;

#[Route('/lodging')]
class LodgingController extends AbstractController
{
    // public function __construct(private ManagerRegistry $doctrine)
    // {}
    

    #[Route('/', name: 'app_lodging_index', methods: ['GET'])]
    public function index(LodgingRepository $lodgingRepository): Response
    {
        return $this->render('lodging/index.html.twig', [
            'lodgings' => $lodgingRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_lodging_new', methods: ['GET', 'POST'])]
    public function new(Request $request, ManagerRegistry $doctrine, UserInterface $user): Response
    {
        $lodging = new Lodging();
        
        $form = $this->createForm(LodgingType::class, $lodging);
        
        $form->remove('created_at');
        $form->remove('updated_at');
        $form->remove('user');
        $form->handleRequest($request);
        
        $lodging->setUser($user);
        
        $lodging->setCreatedAt(new \DateTime());
        

        if ($form->isSubmitted() && $form->isValid()) {

            $manager = $doctrine->getManager();
            $manager->persist($lodging);
            $manager->flush();

            return $this->redirectToRoute('app_lodging_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('lodging/new.html.twig', [
            
            'lodging' => $lodging,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_lodging_show', methods: ['GET'])]
    public function show(Lodging $lodging, ManagerRegistry $doctrine, $id): Response
    {
        // $criterion = $doctrine->getRepository(Criteria::class)->findBy([], ["type" => "DESC"]);
        $criterion = $lodging->getCriteria();
        return $this->render('lodging/show.html.twig', [
            'lodging' => $lodging,
            'criterion' => $criterion
        ]);
    }

    #[Route('/{id}/edit', name: 'app_lodging_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Lodging $lodging, ManagerRegistry $doctrine): Response
    {
        
        $form = $this->createForm(LodgingType::class, $lodging);
        $form->remove('user');
        $form->remove('created_at');
        $form->remove('updated_at');
        $form->handleRequest($request);
        $lodging->setUpdatedAt(new \DateTime());

        if ($form->isSubmitted() && $form->isValid()) {
           
            $manager = $doctrine->getManager();
            $manager->persist($lodging);
            $manager->flush();

            return $this->redirectToRoute('app_lodging_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('lodging/edit.html.twig', [
            'lodging' => $lodging,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_lodging_delete', methods: ['POST'])]
    public function delete(Request $request, Lodging $lodging, LodgingRepository $lodgingRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$lodging->getId(), $request->request->get('_token'))) {
            $lodgingRepository->remove($lodging, true);
        }

        return $this->redirectToRoute('app_lodging_index', [], Response::HTTP_SEE_OTHER);
    }
}
