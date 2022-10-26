<?php

namespace App\Controller;

use App\Entity\Carte;

use App\Form\AddCardType;

use App\Repository\CarteRepository;
use App\Repository\ClasseRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminController extends AbstractController
{
    /**
     * @Route("/admin/cartes", name="show_cards")
     * @IsGranted("ROLE_ADMIN", message="No access! Get out!")
     */
    public function showCards(CarteRepository $repo): Response
    {
        $cartes = $repo->findAll();
        
        return $this->render('admin/showCartes.html.twig', [
            'cartes' => $cartes,
        ]);
    }

    /**
     * @Route("/admin/createCard", name="create_card")
     * @IsGranted("ROLE_ADMIN", message="No access! Get out!")
     */
    public function createCard(Request $request, ClasseRepository $repo, EntityManagerInterface $entityManager): Response
    {
        $carte = new Carte;
        $classes = $repo->findAll();
        
        
        $form = $this->createForm(AddCardType::class, $carte, [
            'cl' => $classes
        ]);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            
            if (null !==  $form['imagePerso']->getData()) {
                $uploadedFile = $form['imagePerso']->getData();
                $destination = $this->getParameter('kernel.project_dir').'/public/images/cartes/personnages/';
                
                $originalFilename = pathinfo($uploadedFile->getClientOriginalName(), PATHINFO_FILENAME).'.'.$uploadedFile->guessExtension();;
                
                $uploadedFile->move($destination, $originalFilename);
                $carte->setImagePerso($originalFilename);
                
            }
            
            $entityManager->persist($carte);
            $entityManager->flush();
            
            $this->addFlash('success', 'Carte crée');
            
            return $this->redirectToRoute('create_card');
        }
        
        return $this->render('admin/addCard.html.twig', [
            'addCardForm' => $form->createView(),
        ]);
    }

    /**
     * @Route("/admin/editCard/{cardId}", name="edit_card")
     * @IsGranted("ROLE_ADMIN", message="No access! Get out!")
     */
    public function editCard($cardId, Request $request, CarteRepository $repo, ClasseRepository $repo2, EntityManagerInterface $entityManager): Response
    {
        $carte = $repo->find($cardId);
        $classes = $repo2->findAll();

        $form = $this->createForm(AddCardType::class, $carte, [
            'cl' => $classes
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            if (null !==  $form['imagePerso']->getData()) {
                $uploadedFile = $form['imagePerso']->getData();
                $destination = $this->getParameter('kernel.project_dir').'/public/images/cartes/personnages/';
                

                $originalFilename = pathinfo($uploadedFile->getClientOriginalName(), PATHINFO_FILENAME).'.'.$uploadedFile->guessExtension();;

                
                if(null !== $carte->getImagePerso()) {
                    $oldFile = $destination.$carte->getImagePerso();
                    

                    if (file_exists($oldFile)) {
                                unlink($oldFile);
                    }
                }

                $uploadedFile->move($destination, $originalFilename);
                $carte->setImagePerso($originalFilename);
            }

            $entityManager->flush();
            
            $this->addFlash('success', 'Carte modifiée');
            
            return $this->redirectToRoute('show_cards');
        }
        
        return $this->render('admin/editCard.html.twig', [
            'addCardForm' => $form->createView(),
            'carte' => $carte
        ]);
    }

    /**
     * @Route("/admin/deleteCard/{cardId}", name="delete_card")
     * @IsGranted("ROLE_ADMIN", message="No access! Get out!")
     */
    public function deleteCard($cardId, Request $request, CarteRepository $repo, EntityManagerInterface $entityManager): Response
    {
        if (!empty($cardId)) {
            $carte = $repo->deleteCard($cardId);

            $this->addFlash('error', 'Carte supprimée !');

        } else $this->addFlash('error', 'Carte inexistante !');
        
        return $this->redirectToRoute('show_cards');
    }
}
