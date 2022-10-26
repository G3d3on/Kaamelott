<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class IntroductionController extends AbstractController
{
    /**
     * @Route("/Présentation-du-jeu", name="presentation")
     */
    public function presentation(): Response
    {
        return $this->render('introduction/presentation.html.twig', [
            'controller_name' => 'IntroductionController',
        ]);
    }

    /**
     * @Route("/Guide-du-débutant", name="guide")
     */
    public function guide(): Response
    {
        return $this->render('introduction/guide.html.twig', [
            'controller_name' => 'IntroductionController',
        ]);
    }

    /**
     * @Route("/L'univers-de-kaamelott", name="univers")
     */
    public function univers(): Response
    {
        return $this->render('introduction/univers.html.twig', [
            'controller_name' => 'IntroductionController',
        ]);
    }
}
