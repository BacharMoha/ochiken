<?php

namespace App\Controller;

use App\Entity\Boisson;
use App\Entity\Sandwitch;
use App\Entity\Tendance;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ChikenController extends AbstractController
{
    #[Route('/chiken', name: 'app_chiken')]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $boissons = $entityManager->getRepository(Boisson::class)->findBy([], ['id' => 'ASC'], 3);
        $boisson = $entityManager->getRepository(Boisson::class)->findBy([], ['id' => 'DESC'], 3);
        $tendance = $entityManager->getRepository(Tendance::class)->findAll();

        $sandwich = $entityManager->getRepository(Sandwitch::class)->findAll();
        return $this->render('chiken/index.html.twig', [
            'boissons'=>$boissons,
            'boisson'=>$boisson,
            'sandwichs'=>$sandwich,
            'tendance'=>$tendance,
        ]);
    }
}
