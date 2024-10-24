<?php

namespace App\Controller;

use App\Entity\Chiken;
use App\Entity\Tendance;
use App\Entity\Texte;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class IndexController extends AbstractController
{
    #[Route('/', name: 'app_index')]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $chikens = $entityManager->getRepository(Chiken::class)->findAll();
        $tendance = $entityManager->getRepository(Tendance::class)->findAll();
        $poulet = $entityManager->getRepository(Chiken::class)->findBy([], null, 2);

        $texte = $entityManager->getRepository(Texte::class)->findBy([], ['id' => 'DESC'], 1);
        return $this->render('index/index.html.twig', [
            'chikens' => $chikens,
            'texte' => $texte,
            'poulets' => $poulet,
            'tendance' => $tendance,
        ]);
    }
}
