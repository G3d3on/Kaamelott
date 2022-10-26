<?php

namespace App\Controller;

use App\Entity\Possede;

use App\Repository\CarteRepository;
use App\Repository\PossedeRepository;
use Doctrine\ORM\EntityManagerInterface;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class BoutiqueController extends AbstractController
{
    /**
     * @Route("/boutique", name="boutique")
     * @IsGranted("ROLE_USER", message="No access! Get out!")
     */
    public function boutique(CarteRepository $carte): Response
    {

        // Recupere toutes les cartes enregistées en base de données
        $cartes = $carte->findAll();

        return $this->render('boutique/boutique.html.twig', [
            'cartes' => $cartes,
        ]);
    }

    /**
     * @Route("/boutique/buy/{cardId}", name="buy_card", methods={"GET"})
     * @IsGranted("ROLE_USER", message="No access! Get out!")
     */
    public function buyCard($cardId, EntityManagerInterface $entityManager, CarteRepository $repo, PossedeRepository $repo2): Response
    {

        // Recupere l'utilisateur en session
        $user = $this->getUser();

        // Recupere l'id de l'utilisateur en session 
        $userId = $user->getId();

        // Recupere le montant de la bourse de l'utilisateur
        $userMoney = $user->getArgent();        

        // Recupere la carte en fonction de la valeur de l'id en parametre
        $carte = $repo->find($cardId);

        // SI la possession n'est pas null ou false ou vide
        if (!empty($carte)) {
            // ALORS on recupere le prix de la carte
            $prixCarte = $carte->getPrix();
    
            // On recupere les possessions de l'utilisateur grace a la valeur de l'id passer en parametre
            $possessions = $repo2->findPossessionsByUser($userId);
    
            // SI l'argent de l'utilisateur et SUPERIEUR ou EGALE au prix de la carte
            if ($userMoney >= $prixCarte) {
                // ALORS on déduit le prix de la carte a la bourse de l'utilisateur 
                $removeMoney = $userMoney - $prixCarte;

                // On met a jour la valeur de la bourse de l'utilisateur
                $user->setArgent($removeMoney);

                // Pour chaque possessions de l'utilisateur 
                foreach ($possessions as $possession) {
                    // SI la valeur de l'id de la carte lié a la possession est EGALE a la valeur de l'id de la carte obtenu via l'url  
                    if ($possession->getCarte()->getId() == $cardId) {
                        // ALORS on recupere le nombre d'exemplaires de la possession
                        $nbExemplaires = $possession->getNbexemplaires();

                        // On met a jour le nombre d'exemplaires en n'en rajoutant 1
                        $possession->setNbexemplaires($nbExemplaires + 1);

                        // Met a jour la base de données avec les nouvelles données
                        $entityManager->flush();
                        
                        $this->addFlash('success', '1x '.ucwords($carte->getNom()).' acheter');

                        // Redirection vers la vue boutique
                        return $this->redirectToRoute('boutique');
                    } 
                }

                // Dans le cas l'utilisateur ne possede pas la carte en question

                // Crée un nouvel objet Possede
                $possede = new Possede();

                // Defini l'utilisateur a associé a la nouvelle possession
                $possede->setUser($user);
                
                // Defini la carte a associé a la nouvelle possession
                $possede->setCarte($carte);

                // Defini le nombre d'exemplaires a 1 de la nouvelle possession
                $possede->setNbexemplaires(1);

                // Prépare l'entité pour la liaison a la base de données, et insert les données en base.  
                $entityManager->persist($possede);

                // Insert les données en base de donnée
                $entityManager->flush();
                
                $this->addFlash('success', '1x'.ucwords($carte->getNom()).' acheter');

                // Redirige l'utilisateur vers la vue de la boutique
                return $this->redirectToRoute('boutique');
            }
            $this->addFlash('error', 'Vous n\'avez pas les fonds nécessaires !');
            return $this->redirectToRoute('boutique');
        }
        //@TODO si la carte n'existe pas renvoyer vers une erreur 404 not found
    }
}