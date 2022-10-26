<?php

namespace App\Controller;

use App\Entity\Deck;
use App\Entity\Compose;

use Ratchet\ConnectionInterface;
use App\Form\RegistrationFormType;
use App\Repository\DeckRepository;
use App\Repository\UserRepository;
use App\Repository\CarteRepository;

use App\Repository\ComposeRepository;
use App\Repository\PossedeRepository;
use Ratchet\MessageComponentInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserController extends AbstractController
{

    protected $clients;

    public function __construct()
    {
        $this->clients = new \SplObjectStorage;
    }

    /**
     * @Route("/profil", name="profil")
     * @IsGranted("ROLE_USER", message="No access! Get out!")
     */
    public function profil(UserRepository $users): Response
    {

        return $this->render('user/profil.html.twig');
    }

    /**
     * @Route("/profil/edit", name="user_edit")
     * @IsGranted("ROLE_USER", message="No access! Get out!")
     */
    public function editUser(Request $request, UserPasswordHasherInterface $userPasswordHasherInterface, EntityManagerInterface $entityManager )
    {
        $user = $this->getUser();
        $userEmail = $user->getEmail();

        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->remove('plainPassword');
        $form->remove('agreeTerms');
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $entityManager->flush();
            
            $this->addFlash('success', 'Profil modifié');

            if ($userEmail !== $form->get('email')->getData()) {
                
                return $this->redirectToRoute('app_logout');
            }
            
            return $this->redirectToRoute('profil');
        }
        
        return $this->render('user/edit.html.twig', [
            'editForm' => $form->createView(),
        ]);
    }

     /**
     * @Route("/profil/editbio", name="edit_bio",  methods={"GET", "POST"})
     * @IsGranted("ROLE_USER", message="No access! Get out!")
     */
    public function editBio(Request $request, EntityManagerinterface $entityManager): Response
    {

        $user = $this->getUser();
        
        $text = $request->request->get('biographie');
    
        if (isset($text)) {
            $user->setBio($text);
            $entityManager->flush();
            
            $this->addFlash('success', 'Biographie modifiée');

            return $this->redirectToRoute('profil');
        }

        return $this->render('user/editBio.html.twig');

    }

    /**
     * @Route("/cartes", name="cartes")
     * @IsGranted("ROLE_USER", message="No access! Get out!")
     */
    public function cards(PossedeRepository $repo, ComposeRepository $repo2, Request $request): Response
    {
        $user = $this->getUser();
        $userId = $user->getId();
        
        $deck = $user->getDeck();
        $deckId = $deck->getId();

        $possessions = $repo->findPossessionsByUser($userId, $deckId);
        
        return $this->render('user/cartes.html.twig', [
            'possessions' => $possessions,
        ]);
    }

    /**
     * @Route("/cartes/sell/{cardId}", name="sell_card", methods={"GET"})
     * @IsGranted("ROLE_USER", message="No access! Get out!")
     */
    public function sellCard($cardId, EntityManagerInterface $entityManager, PossedeRepository $repo, ComposeRepository $repo2): Response
    {
        // Recupere l'utilisateur en session
        $user = $this->getUser();

        // Recupere l'id de l'utilisateur
        $userId = $user->getId();

        // Recupere le deck de l'utilisateur
        $deck = $user->getDeck();

        // Recupere l'id du deck
        $deckId = $deck->getId();

        // Recupere le montant de la bourse de l'utilisateur
        $userMoney = $user->getArgent();  

        // Recupere la possession de l'utilisateur avec l'id de l'utilisateur et de la carte
        $possession = $repo->findPossessionByUserAndCard($userId, $cardId);

        // Recupere la composition du deck avec l'id du deck et de la carte
        $composition = $repo2->findCompositionByDeckAndCard($deckId, $cardId);
        
        // SI la possession n'est pas null ou false ou vide
        if (!empty($possession)) {
            //SI la composition est null ou false ou vide
            if (empty($composition)) {
                // ALORS on recupere le prix de la carte que l'utilisateur souhaite vendre
                $prixCarte = $possession[0]->getCarte()->getPrix();
        
                // On recupere le nombre d'exemplaires de la carte posséder
                $nbExemplaires = $possession[0]->getNbexemplaires();

                // SI le nombre d'exemplaires et strictement supérieur a 0
                if ($nbExemplaires > 0) {
                    // ALORS on soustrait 1 exemplaires de la carte en question
                    $exemplaire = $possession[0]->setNbexemplaires($nbExemplaires - 1);

                    // SI après soustraction l'exemplaire est EGALE a 0
                    if ($exemplaire->getNbexemplaires() == 0) {

                        // ALORS on supprime la possession grace a l'id de l'utilisateur et l'id de la carte passer en parametre
                        $removePossession = $repo->deletePossessionByUserAndCard($userId, $cardId);
                    }
                    // Rajoute l'argent de la valeur de la carte divisé par 2, a la bourse de l'utilisateur
                    $addMoney = $userMoney + ($prixCarte / 2);
    
                    // Remet a jour la valeur de la bourse de l'utilisateur
                    $user->setArgent($addMoney);
    
                    // Met a jour la base de données avec les nouvelles données
                    $entityManager->flush();

                    $this->addFlash('success', ''.ucwords($possession[0]->getCarte()->getNom()).' vendu pour '.($prixCarte / 2).' pièces d\'or.');

                    return $this->redirectToRoute('cartes');
                }
            } else{
                $this->addFlash('error', 'Veuillez retirez tous les exemplaires present dans votre deck avant de les vendres');

                return $this->redirectToRoute('cartes');
            } 
        }
        
        //@TODO si la possession n'existe pas renvoyer vers une erreur 404 not found 
        $this->addFlash('error', 'MÉCRÉAAAAAAAAANT !!!');

        return $this->redirectToRoute('cartes');
    }

    /**
     * @Route("/cartes/assign/{cardId}", name="assign_cardToDeck", methods={"GET"})
     * @IsGranted("ROLE_USER", message="No access! Get out!")
     */
    public function addCardToDeck($cardId, Request $request, CarteRepository $repo,  PossedeRepository $repo2, ComposeRepository $repo3, EntityManagerInterface $entityManager): Response
    {
        // Recupere l'utilisateur en session
        $user = $this->getUser();

        // Recupere l'id de l'utilisateur
        $userId = $user->getId();

        // Recupere le deck de l'utilisateur
        $deck = $user->getDeck();

        // Recupere l'id du deck
        $deckId = $deck->getId();
        
        // Recupere l'objet carte selon l'id passer en parametre
        $carte = $repo->find($cardId);
        
        // Recupere la possession de l'utilisateur avec l'id de l'utilisateur et de la carte
        $possession = $repo2->findPossessionByUserAndCard($userId, $cardId);

        // Recupere la composition du deck avec l'id du deck et de la carte
        $composition = $repo3->findCompositionByDeckAndCard($deckId, $cardId);

        // SI la possession n'est pas null ou false ou vide
        if (!empty($possession)) {
            //SI la composition est null ou false ou vide
            if (empty($composition)) {
                // ALORS on crée un nouvel objet compose
                $compose = new Compose();
                
                // Definie le deck a associer a la nouvelle composition
                $compose->setDeck($deck);

                // Definie le nombre d'exemplaire a 1 
                $compose->setNbexemplaires(1);

                // Definie l'objet carte a associer a la nouvelle composition
                $compose->setCarte($carte);

                // Prépare l'entité pour la liaison a la base de données 
                $entityManager->persist($compose);

                // Insert les données en base de donnée 
                $entityManager->flush();
        
                $this->addFlash('success', ''.ucwords($carte->getNom()).' assignée au deck');

            // SINON SI la composition n'est pas null et que le nombre d'exemplaires de la composition
            // est strictement inferieur aux nombres d'exemplaires posseder 
            } elseif ($composition[0]->getNbexemplaires() < $possession[0]->getNbexemplaires()) {
                // ALORS on recupere le nombre d'exemplaire de la composition
                $nbExempCompos = $composition[0]->getNbexemplaires();

                // On met a jour le nombre d'exemplaires en n'en rajoutant 1
                $composition[0]->setNbexemplaires($nbExempCompos + 1);
            
                // Insert les données en base de donnée
                $entityManager->flush();
            
                $this->addFlash('success', ''.ucwords($carte->getNom()).' assignée au deck');

            } else $this->addFlash('error', 'Vous ne possédez pas assez d\'exemplaires de la carte : '.ucwords($carte->getNom()).'');
    
        } else $this->addFlash('error', 'Vous ne possédez pas cette carte jeune brigand !!');

        return $this->redirectToRoute('cartes');
    }

    /**
     * @Route("/cartes/unassign/{cardId}", name="remove_cardFromDeck", methods={"GET"})
     * @IsGranted("ROLE_USER", message="No access! Get out!")
     */
    public function removeCardFromDeck($cardId, ComposeRepository $repo, EntityManagerInterface $entityManager): Response
    {

        // Recupere l'utilisateur en session
        $user = $this->getUser();

        // Recupere le deck de l'utilisateur
        $deck = $user->getDeck(); 

        // Recupere l'id du deck
        $deckId = $deck->getId();
        
        // Recupere la composition du deck avec l'id du deck et de la carte
        $composition = $repo->findCompositionByDeckAndCard($deckId, $cardId);

        // SI la possession n'est pas null ou false ou vide
        if (!empty($composition)) {
            // ALORS on recupere le nombre d'exemplaires de la composition
            $nbExemplaires = $composition[0]->getNbexemplaires();
            // SI le nombre d'exemplaires est strictement supérieur a 0
            if ($nbExemplaires > 0) {
                // ALORS on met a jour le nombre d'exemplaires -1
                $exemplaire = $composition[0]->setNbexemplaires($nbExemplaires - 1);
                // SI le nombre d'exemplaires après retrait d'un exemplaire
                if ($exemplaire->getNbexemplaires() == 0) {
                    // ALORS on recupère l'id de la composition
                    $compositionId = $composition[0]->getId();
                    // On supprime la composition de la base de données 
                    $removeComposition = $repo->deleteComposition($compositionId);
    
                    $this->addFlash('success', 'La carte '.ucwords($composition[0]->getCarte()->getNom()).' a été rétirée du deck.');
                   
                    return $this->redirectToRoute('cartes'); 
                }
                $this->addFlash('success', '1 exemplaire de la carte '.ucwords($composition[0]->getCarte()->getNom()).' a été retirée du deck.');

                // Insert les données en base de donnée
                $entityManager->flush();

                return $this->redirectToRoute('cartes');
            }
        } 
        $this->addFlash('error', 'Aucun exemplaire à retirer du deck !');

        return $this->redirectToRoute('cartes');
    }

    /**
     * @Route("/deck", name="deck")
     * @IsGranted("ROLE_USER", message="No access! Get out!")
     */
    public function deck(DeckRepository $repo): Response
    {
        $user = $this->getUser();
        $userId = $user->getId();
        
        $deck = $repo->findDeckByUser($userId);

        return $this->render('user/deck.html.twig', [
            'deck' => $deck,
        ]);
    }
    

    /**
     * @Route("/deck/{id}/detail", name="deck_detail", methods={"GET"})
     * @IsGranted("ROLE_USER", message="No access! Get out!")
     */
    public function deckDetail(): Response
    {
        
        $user = $this->getUser();
        $deck = $user->getDeck(); 

        $compositions = $deck->getComposes();
    

        return $this->render('user/deckDetail.html.twig', [
            'compositions' => $compositions,
        ]);
    }
}
