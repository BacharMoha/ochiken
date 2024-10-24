<?php

namespace App\Controller;

use App\Entity\Boisson;
use App\Entity\Categorie;
use App\Entity\Chiken;
use App\Entity\Menuvend;
use App\Entity\Sandwitch;
use App\Entity\Tendance;
use App\Entity\Texte;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ModificationController extends AbstractController
{
    #[Route('/modification/{id}', name: 'app_modifierboisson')]
    public function modifierboisson(int $id, Request $request, EntityManagerInterface $entityManager): Response
    {
        $boisson = $entityManager->getRepository(Boisson::class)->find($id);

        if (!$boisson) {
            throw $this->createNotFoundException('La boisson demandée n\'existe pas.');
        }

        if ($request->isMethod('POST')) {
            $libelle = $request->request->get('titre');
            $prix = $request->request->get('prix');
            $image = $request->files->get('image');
            
          
            // Mise à jour des données de la medicament
            $boisson->setLibelle($libelle);
            $boisson->setPrix($prix);
            if ($image) {
                $imageName = uniqid().'.'.$image->guessExtension();
                $image->move($this->getParameter('images_directory'), $imageName);
                $boisson->setImage($imageName);
                
            }

            // Mise à jour du mot de passe si fourni
           

            // Sauvegarde les modifications dans la base de données
            $entityManager->flush();

            return $this->redirectToRoute('app_gererboisson');
        }

        return $this->render('modification/modifier_boisson.html.twig', [
            'boisson' => $boisson,
        ]);
    }


    #[Route('/boisson/delete/{id}', name: 'app_delete_boisson', methods: ['POST'])]
    public function deleteboisson(int $id, EntityManagerInterface $entityManager): RedirectResponse
    {
        // Trouver la notification à supprimer
        $boisson = $entityManager->getRepository(Boisson::class)->find($id);

        if ($boisson) {
            // Supprimer la boisson
            $entityManager->remove($boisson);
            $entityManager->flush();
        }

        // Rediriger vers une autre page après suppression
        return $this->redirectToRoute('app_gererboisson');
    }

    #[Route('/modificationchiken/{id}', name: 'app_modifierchicken')]
    public function modifierchiken(int $id, Request $request, EntityManagerInterface $entityManager): Response
    {
        $chiken = $entityManager->getRepository(Chiken::class)->find($id);

        $categories = $entityManager->getRepository(Categorie::class)->findBy([], null, 2);


        if (!$chiken) {
            throw $this->createNotFoundException('La chiken demandée n\'existe pas.');
        }

        if ($request->isMethod('POST')) {
            $libelle = $request->request->get('titre');
            $prix = $request->request->get('prix');
            $piece = $request->request->get('piece');
            $image = $request->files->get('image');
            $categorieId = $request->request->get('categorie');
            $categorie = $entityManager->getRepository(Categorie::class)->find($categorieId);

          
            // Mise à jour des données de la medicament
            $chiken->setLibelle($libelle);
            $chiken->setPiece($piece);
            $chiken->setPrix($prix);
            $chiken->setIdcategorie($categorie);
            if ($image) {
                $imageName = uniqid().'.'.$image->guessExtension();
                $image->move($this->getParameter('images_directory'), $imageName);
                $chiken->setImage($imageName);
                
            }

            // Mise à jour du mot de passe si fourni
           

            // Sauvegarde les modifications dans la base de données
            $entityManager->flush();

            return $this->redirectToRoute('app_gererchicken');
        }
        return $this->render('modification/modifier_chiken.html.twig', [
            'chiken' => $chiken,
            'categories' => $categories,
        ]);
    }

    #[Route('/chiken/delete/{id}', name: 'app_delete_chiken', methods: ['POST'])]
    public function deletechiken(int $id, EntityManagerInterface $entityManager): RedirectResponse
    {
        // Trouver la notification à supprimer
        $chiken = $entityManager->getRepository(Chiken::class)->find($id);

        if ($chiken) {
            // Supprimer la chiken
            $entityManager->remove($chiken);
            $entityManager->flush();
        }

        // Rediriger vers une autre page après suppression
        return $this->redirectToRoute('app_gererchicken');
    }


    #[Route('/modificationjournee/{id}', name: 'app_modifierjournee')]
    public function modifierjournnee(int $id,Request $request, EntityManagerInterface $entityManager): Response
    {
        $menuspec = $entityManager->getRepository(Menuvend::class)->find($id);

        if (!$menuspec) {
            throw $this->createNotFoundException('La menuspec demandée n\'existe pas.');
        }

        if ($request->isMethod('POST')) {
            $libelle = $request->request->get('titre');
            $prix = $request->request->get('prix');
            $image = $request->files->get('image');
            
          
            // Mise à jour des données de la medicament
            $menuspec->setLibelle($libelle);
            $menuspec->setPrix($prix);
            if ($image) {
                $imageName = uniqid().'.'.$image->guessExtension();
                $image->move($this->getParameter('images_directory'), $imageName);
                $menuspec->setImage($imageName);
                
            }

            // Mise à jour du mot de passe si fourni
           

            // Sauvegarde les modifications dans la base de données
            $entityManager->flush();

            return $this->redirectToRoute('app_gererjournee');
        }

        return $this->render('modification/modifier_journee_special.html.twig', [
            'menuspec' => $menuspec,
        ]);
    }


    #[Route('/spec/delete/{id}', name: 'app_delete_spec', methods: ['POST'])]
    public function deletespec(int $id, EntityManagerInterface $entityManager): RedirectResponse
    {
        // Trouver la notification à supprimer
        $menuspec = $entityManager->getRepository(Menuvend::class)->find($id);

        if ($menuspec) {
            // Supprimer la menuspec
            $entityManager->remove($menuspec);
            $entityManager->flush();
        }

        // Rediriger vers une autre page après suppression
        return $this->redirectToRoute('app_gererjournee');
    }
    #[Route('/modificationsandwich/{id}', name: 'app_modifiersandwich')]
    public function modifiersandwich(int $id, Request $request, EntityManagerInterface $entityManager): Response
    {
        $categories = $entityManager->getRepository(Categorie::class)->findBy([], ['id' => 'DESC'], 2);


        $sandwich = $entityManager->getRepository(Sandwitch::class)->find($id);

        if (!$sandwich) {
            throw $this->createNotFoundException('La sandwich demandée n\'existe pas.');
        }

        if ($request->isMethod('POST')) {
            $libelle = $request->request->get('titre');
            $prix = $request->request->get('prix');
            $description = $request->request->get('description');
            $image = $request->files->get('image');
            $categorieId = $request->request->get('categorie');
            $categorie = $entityManager->getRepository(Categorie::class)->find($categorieId);
            
          
            // Mise à jour des données de la medicament
            $sandwich->setLibelle($libelle);
            $sandwich->setPrix($prix);
            $sandwich->setIdcategorie($categorie);
            $sandwich->setDescription($description);
            if ($image) {
                $imageName = uniqid().'.'.$image->guessExtension();
                $image->move($this->getParameter('images_directory'), $imageName);
                $sandwich->setImage($imageName);
                
            }

            // Mise à jour du mot de passe si fourni
           

            // Sauvegarde les modifications dans la base de données
            $entityManager->flush();

            return $this->redirectToRoute('app_gerersandwitch');
        }
        return $this->render('modification/modifier_sandwitch.html.twig', [
            'sandwich' => $sandwich,
            'categories' => $categories,
        ]);
    }

    #[Route('/sandwich/delete/{id}', name: 'app_delete_sandwich', methods: ['POST'])]
    public function deletesandwich(int $id, EntityManagerInterface $entityManager): RedirectResponse
    {
        // Trouver la notification à supprimer
        $sandwich = $entityManager->getRepository(Sandwitch::class)->find($id);

        if ($sandwich) {
            // Supprimer la sandwich
            $entityManager->remove($sandwich);
            $entityManager->flush();
        }

        // Rediriger vers une autre page après suppression
        return $this->redirectToRoute('app_gerersandwitch');
    }
    #[Route('/modificationtendance/{id}', name: 'app_modifiertendance')]
    public function modifiertendance(int $id, Request $request, EntityManagerInterface $entityManager): Response
    {
        $tendance = $entityManager->getRepository(Tendance::class)->find($id);

        if (!$tendance) {
            throw $this->createNotFoundException('La tendance demandée n\'existe pas.');
        }

        if ($request->isMethod('POST')) {
            $libelle = $request->request->get('titre');
            $prix = $request->request->get('prix');
            $description = $request->request->get('description');
            $image = $request->files->get('image');
            
          
            // Mise à jour des données de la medicament
            $tendance->setLibelle($libelle);
            $tendance->setPrix($prix);
            $tendance->setDescription($description);
            if ($image) {
                $imageName = uniqid().'.'.$image->guessExtension();
                $image->move($this->getParameter('images_directory'), $imageName);
                $tendance->setImage($imageName);
                
            }

            // Mise à jour du mot de passe si fourni
           

            // Sauvegarde les modifications dans la base de données
            $entityManager->flush();

            return $this->redirectToRoute('app_gerertendance');
        }
        return $this->render('modification/modifier_tendance.html.twig', [
            'tendance' => $tendance,
        ]);
    }

    #[Route('/tendance/delete/{id}', name: 'app_delete_tendance', methods: ['POST'])]
    public function deletetendance(int $id, EntityManagerInterface $entityManager): RedirectResponse
    {
        // Trouver la notification à supprimer
        $tendance = $entityManager->getRepository(Tendance::class)->find($id);

        if ($tendance) {
            // Supprimer la tend$tendance
            $entityManager->remove($tendance);
            $entityManager->flush();
        }

        // Rediriger vers une autre page après suppression
        return $this->redirectToRoute('app_gerertendance');
    }

    #[Route('/modificationtexte/{id}', name: 'app_modifiertexte')]
    public function modifiertexte(int $id, Request $request, EntityManagerInterface $entityManager): Response
    {

        $texte = $entityManager->getRepository(Texte::class)->find($id);

        if (!$texte) {
            throw $this->createNotFoundException('La texte demandée n\'existe pas.');
        }

        if ($request->isMethod('POST')) {
            $libelle = $request->request->get('description');
           
            
          
            // Mise à jour des données de la medicament
            $texte->setTexte($libelle);
      

            // Mise à jour du mot de passe si fourni
           

            // Sauvegarde les modifications dans la base de données
            $entityManager->flush();

            return $this->redirectToRoute('app_gerertexte');
        }
        return $this->render('modification/modifier_texte.html.twig', [
            'texte' => $texte,
        ]);
    }
}
