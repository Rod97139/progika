<?php

namespace App\Controller;

use App\Entity\Criteria;
use App\Form\CriteriaType;
use App\Repository\CriteriaRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/criteria')]
// #[IsGranted('ROLE_ADMIN')]
class CriteriaController extends AbstractController
{
    #[Route('/', name: 'app_criteria_index', methods: ['GET'])]
    public function index(CriteriaRepository $criteriaRepository): Response
    {
        return $this->render('criteria/index.html.twig', [
            'criterias' => $criteriaRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_criteria_new', methods: ['GET', 'POST'])]
    public function new(Request $request, CriteriaRepository $criteriaRepository): Response
    {
        $criterion = new Criteria();
        $form = $this->createForm(CriteriaType::class, $criterion);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $criteriaRepository->save($criterion, true);

            return $this->redirectToRoute('app_criteria_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('criteria/new.html.twig', [
            'criterion' => $criterion,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_criteria_show', methods: ['GET'])]
    public function show(Criteria $criterion): Response
    {
        return $this->render('criteria/show.html.twig', [
            'criterion' => $criterion,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_criteria_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Criteria $criterion, CriteriaRepository $criteriaRepository): Response
    {
        $form = $this->createForm(CriteriaType::class, $criterion);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $criteriaRepository->save($criterion, true);

            return $this->redirectToRoute('app_criteria_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('criteria/edit.html.twig', [
            'criterion' => $criterion,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_criteria_delete', methods: ['POST'])]
    public function delete(Request $request, Criteria $criterion, CriteriaRepository $criteriaRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$criterion->getId(), $request->request->get('_token'))) {
            $criteriaRepository->remove($criterion, true);
        }

        return $this->redirectToRoute('app_criteria_index', [], Response::HTTP_SEE_OTHER);
    }
}
