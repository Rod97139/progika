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
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\String\Slugger\SluggerInterface;

#[Route('/lodging')]
class LodgingController extends AbstractController
{

    #[Route('/', name: 'app_lodging_index', methods: ['GET'])]
    public function index(LodgingRepository $lodgingRepository): Response
    {
        return $this->render('lodging/index.html.twig', [
            'lodgings' => $lodgingRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_lodging_new', methods: ['GET', 'POST'])]
    public function new(Request $request, ManagerRegistry $doctrine, UserInterface $user, SluggerInterface $slugger): Response
    {
        
        $routeName = $request->attributes->get('_route');
        $lodging = new Lodging();
        
        $form = $this->createForm(LodgingType::class, $lodging, [
            'routeName' => $routeName
        ]);
        
        $form->remove('created_at');
        $form->remove('updated_at');
        $form->remove('user');
        $form->handleRequest($request);
        
        $lodging->setUser($user);
        
        $lodging->setCreatedAt(new \DateTime());
        

        if ($form->isSubmitted() && $form->isValid()) {

            $file = $form->get('image')->getData();

            // this condition is needed because the 'brochure' field is not required
            // so the PDF file must be processed only when a file is uploaded
            if ($file) {
                $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                // this is needed to safely include the file name as part of the URL
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$file->guessExtension();

                // Move the file to the directory where brochures are stored
                try {
                    $file->move(
                        $this->getParameter('lodging_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }

                // updates the 'brochureFilename' property to store the PDF file name
                // instead of its contents
                $lodging->setImage('/uploads/images/' . $newFilename);
            }

            $manager = $doctrine->getManager();
            $manager->persist($lodging);
            $manager->flush();

            if ($this->isGranted('ROLE_ADMIN')) {
                return $this->redirectToRoute('app_lodging_index', [], Response::HTTP_SEE_OTHER);
            }elseif ($this->isGranted('ROLE_OWNER')) {
                return $this->redirectToRoute('app_owner_lodging_list', [], Response::HTTP_SEE_OTHER);
            }
        }

        return $this->render('lodging/new.html.twig', [
            
            'lodging' => $lodging,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_lodging_show', methods: ['GET'])]
    public function show(Lodging $lodging): Response
    {
        // $criterion = $doctrine->getRepository(Criteria::class)->findBy([], ["type" => "DESC"]);
        $criterion = $lodging->getCriteria();
        return $this->render('lodging/show.html.twig', [
            'lodging' => $lodging,
            'criterion' => $criterion
        ]);
    }

    #[Route('/{id}/edit', name: 'app_lodging_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Lodging $lodging, ManagerRegistry $doctrine, SluggerInterface $slugger): Response
    {
        $routeName = $request->attributes->get('_route');
        $form = $this->createForm(LodgingType::class, $lodging, [
            'routeName' => $routeName
        ]);
        $form->remove('user');
        $form->remove('created_at');
        $form->remove('updated_at');
        $form->remove('departement');
        
        $form->handleRequest($request);
        $lodging->setUpdatedAt(new \DateTime());

        if ($form->isSubmitted() && $form->isValid()) {

            
            $file = $form->get('image')->getData();

            // this condition is needed because the 'brochure' field is not required
            // so the PDF file must be processed only when a file is uploaded
            if ($file) {
                $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                // this is needed to safely include the file name as part of the URL
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$file->guessExtension();

                // Move the file to the directory where brochures are stored
                try {
                    $file->move(
                        $this->getParameter('lodging_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }

                // updates the 'brochureFilename' property to store the PDF file name
                // instead of its contents
                $lodging->setImage('/uploads/images/' . $newFilename);
            }
           
            $manager = $doctrine->getManager();
            $manager->persist($lodging);
            $manager->flush();

            if ($this->isGranted('ROLE_ADMIN')) {
                return $this->redirectToRoute('app_lodging_index', [], Response::HTTP_SEE_OTHER);
            }elseif ($this->isGranted('ROLE_OWNER')) {
                return $this->redirectToRoute('app_owner_lodging_list', [], Response::HTTP_SEE_OTHER);
            }
        }

        return $this->renderForm('lodging/edit.html.twig', [
            'lodging' => $lodging,
            'form' => $form,
        ]);
    }

    

    #[Route('/delete/{id}', name: 'app_lodging_delete', methods: ['POST'])]
    public function delete(Request $request, Lodging $lodging, LodgingRepository $lodgingRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$lodging->getId(), $request->request->get('_token'))) {
            $lodgingRepository->remove($lodging, true);
        }

        if ($this->isGranted('ROLE_ADMIN')) {
            return $this->redirectToRoute('app_lodging_index', [], Response::HTTP_SEE_OTHER);
        }elseif ($this->isGranted('ROLE_OWNER')) {
            return $this->redirectToRoute('app_owner_lodging_list', [], Response::HTTP_SEE_OTHER);
        }
    }
}
