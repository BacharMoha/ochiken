<?php

namespace App\Controller;

use App\Entity\Boisson;
use App\Entity\Categorie;
use App\Entity\Chiken;
use App\Entity\Menuvend;
use App\Entity\Sandwitch;
use App\Entity\Tendance;
use App\Entity\Texte;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;

class AdminController extends AbstractController
{
    #[Route('/admin', name: 'app_admin')]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $chiken = $entityManager->getRepository(Chiken::class)->findAll();
        $sandwich = $entityManager->getRepository(Sandwitch::class)->findAll();
        $boisson = $entityManager->getRepository(Boisson::class)->findAll();

        return $this->render('admin/index.html.twig', [
            'chiken' => $chiken,
            'sandwich' => $sandwich,
            'boisson' => $boisson,
        ]);
    }

    #[Route('admin/ajouter_boissoins', name: 'app_boissons')]
    public function bssns(Request $request, EntityManagerInterface $entityManager): Response
    {
        if ($request->isMethod('POST')) {
            // Récupérer les données du formulaire
            $libelle = $request->request->get('titre');
            $prix = $request->request->get('prix');
            $image = $request->files->get('image'); // Gestion du fichier

            // Valider les données du formulaire
            if ($libelle && $prix) {
                // Créer un nouvel objet Boisson
                $boisson = new Boisson();
                $boisson->setLibelle($libelle);
                $boisson->setPrix((int)$prix);

                // Gérer le téléchargement de l'image (si présente)
                if ($image) {
                    $imageName = uniqid().'.'.$image->guessExtension();
                    $image->move(
                        $this->getParameter('images_directory'), // Répertoire où stocker l'image
                        $imageName
                    );
                    $boisson->setImage($imageName);
                }

                // Sauvegarder l'entité dans la base de données
                $entityManager->persist($boisson);
                $entityManager->flush();

                // Ajouter un message flash pour confirmation
                $this->addFlash('success', 'La boisson a été ajoutée avec succès !');

                // Redirection vers une autre page (par exemple la liste des boissons)
                return $this->redirectToRoute('app_gererboisson');
            } else {
                // En cas d'erreur, ajouter un message flash
                $this->addFlash('error', 'Tous les champs sont obligatoires.');
            }
        }
        return $this->render('admin/ajouter_boisson.html.twig', [
            'controller_name' => 'AdminController',
        ]);
    }


    #[Route('admin/ajouter_chikenss', name: 'app_chikenss')]
    public function chkns(Request $request, EntityManagerInterface $entityManager): Response
    {
        $categories = $entityManager->getRepository(Categorie::class)->findBy([], null, 2);

        if ($request->isMethod('POST')) {
            // Récupérer les données du formulaire
            $libelle = $request->request->get('titre');
            $piece = $request->request->get('piece');
            $prix = $request->request->get('prix');
            $categorieId = $request->request->get('categorie');
            $image = $request->files->get('image');

            // Valider les données du formulaire
            if ($libelle && $piece && $prix && $categorieId) {
                // Créer un nouvel objet Chiken
                $chiken = new Chiken();
                $chiken->setLibelle($libelle);
                $chiken->setPiece((int)$piece);
                $chiken->setPrix((int)$prix);

                // Récupérer la catégorie sélectionnée
                $categorie = $entityManager->getRepository(Categorie::class)->find($categorieId);
                if ($categorie) {
                    $chiken->setIdcategorie($categorie);
                }

                // Gérer l'image (si présente)
                if ($image) {
                    $imageName = uniqid().'.'.$image->guessExtension();
                    $image->move($this->getParameter('images_directory'), $imageName);
                    $chiken->setImage($imageName);
                }

                // Sauvegarder le chiken
                $entityManager->persist($chiken);
                $entityManager->flush();

                // Message de succès
                $this->addFlash('success', 'Chiken ajouté avec succès !');

                return $this->redirectToRoute('app_gererchicken'); // Redirection vers la liste des chikens
            } else {
                $this->addFlash('error', 'Tous les champs sont obligatoires.');
            }
        }
        return $this->render('admin/ajouter_chiken.html.twig', [
            'categories' => $categories
        ]);
    }


    #[Route('admin/ajouter_sandwitch', name: 'app_sandwitch')]
    public function sandwtch(Request $request, EntityManagerInterface $entityManager): Response
    {
        $categories = $entityManager->getRepository(Categorie::class)->findBy([], ['id' => 'DESC'], 2);

        if ($request->isMethod('POST')) {
            // Récupérer les données du formulaire
            $libelle = $request->request->get('titre');
            $prix = $request->request->get('prix');
            $description = $request->request->get('description');
            $categorieId = $request->request->get('categorie');
            $image = $request->files->get('image');

            // Valider les données du formulaire
            if ($libelle && $prix && $description && $categorieId) {
                // Créer un nouvel objet sandwich
                $sandwich = new Sandwitch();
                $sandwich->setLibelle($libelle);
                $sandwich->setPrix((int)$prix);
                $sandwich->setDescription($description);

                // Récupérer la catégorie sélectionnée
                $categorie = $entityManager->getRepository(Categorie::class)->find($categorieId);
                if ($categorie) {
                    $sandwich->setIdcategorie($categorie);
                }

                // Gérer l'image (si présente)
                if ($image) {
                    $imageName = uniqid().'.'.$image->guessExtension();
                    $image->move($this->getParameter('images_directory'), $imageName);
                    $sandwich->setImage($imageName);
                }

                // Sauvegarder le sandwich
                $entityManager->persist($sandwich);
                $entityManager->flush();

                // Message de succès
                $this->addFlash('success', 'sandwich ajouté avec succès !');

                return $this->redirectToRoute('app_gerersandwitch'); // Redirection vers la liste des chikens
            } else {
                $this->addFlash('error', 'Tous les champs sont obligatoires.');
            }
        }

        return $this->render('admin/ajouter_sandwitch.html.twig', [
            'categories' => $categories
        ]);
    }
    #[Route('admin/ajouter_journee', name: 'app_journee')]
    public function jrnn(Request $request, EntityManagerInterface $entityManager): Response
    {
        if ($request->isMethod('POST')) {
            // Récupérer les données du formulaire
            $libelle = $request->request->get('titre');
            $prix = $request->request->get('prix');
            $image = $request->files->get('image'); // Gestion du fichier

            // Valider les données du formulaire
            if ($libelle && $prix) {
                // Créer un nouvel objet Boisson
                $menuspec = new Menuvend();
                $menuspec->setLibelle($libelle);
                $menuspec->setPrix((int)$prix);

                // Gérer le téléchargement de l'image (si présente)
                if ($image) {
                    $imageName = uniqid().'.'.$image->guessExtension();
                    $image->move(
                        $this->getParameter('images_directory'), // Répertoire où stocker l'image
                        $imageName
                    );
                    $menuspec->setImage($imageName);
                }

                // Sauvegarder l'entité dans la base de données
                $entityManager->persist($menuspec);
                $entityManager->flush();

                // Ajouter un message flash pour confirmation
                $this->addFlash('success', 'La boisson a été ajoutée avec succès !');

                // Redirection vers une autre page (par exemple la liste des boissons)
                return $this->redirectToRoute('app_gererjournee');
            } else {
                // En cas d'erreur, ajouter un message flash
                $this->addFlash('error', 'Tous les champs sont obligatoires.');
            }
        }
        return $this->render('admin/journee_special.html.twig', [
            'controller_name' => 'AdminController',
        ]);
    }
    #[Route('admin/menu_tendance', name: 'app_tendance')]
    public function tndnc(Request $request, EntityManagerInterface $entityManager): Response
    {
        if ($request->isMethod('POST')) {
            // Récupérer les données du formulaire
            $libelle = $request->request->get('titre');
            $prix = $request->request->get('prix');
            $description = $request->request->get('description');
            $image = $request->files->get('image'); // Gestion du fichier

            // Valider les données du formulaire
            if ($libelle && $prix) {
                // Créer un nouvel objet Boisson
                $menutend = new Tendance();
                $menutend->setLibelle($libelle);
                $menutend->setDescription($description);
                $menutend->setPrix((int)$prix);

                // Gérer le téléchargement de l'image (si présente)
                if ($image) {
                    $imageName = uniqid().'.'.$image->guessExtension();
                    $image->move(
                        $this->getParameter('images_directory'), // Répertoire où stocker l'image
                        $imageName
                    );
                    $menutend->setImage($imageName);
                }

                // Sauvegarder l'entité dans la base de données
                $entityManager->persist($menutend);
                $entityManager->flush();

                // Ajouter un message flash pour confirmation
                $this->addFlash('success', 'La boisson a été ajoutée avec succès !');

                // Redirection vers une autre page (par exemple la liste des boissons)
                return $this->redirectToRoute('app_gerertendance');
            } else {
                // En cas d'erreur, ajouter un message flash
                $this->addFlash('error', 'Tous les champs sont obligatoires.');
            }
        }
        return $this->render('admin/menu_tendance.html.twig', [
            'controller_name' => 'AdminController',
        ]);
    }
    #[Route('admin/gerer_chicken', name: 'app_gererchicken')]
    public function gererchicken(EntityManagerInterface $entityManager): Response
    {
        $chiken = $entityManager->getRepository(Chiken::class)->findAll();


        return $this->render('admin/gerer_chicken.html.twig', [
            'chikens' => $chiken,
        ]);
    }
    #[Route('admin/gerer_boisson', name: 'app_gererboisson')]
    public function gererboisson(EntityManagerInterface $entityManager): Response
    {
        $boissons = $entityManager->getRepository(Boisson::class)->findAll();
        
        return $this->render('admin/gerer_boisson.html.twig', [
            'boissons'=>$boissons,
        ]);
    }
    #[Route('admin/gerer_journee', name: 'app_gererjournee')]
    public function gererjournee(EntityManagerInterface $entityManager): Response
    {
        $journespec = $entityManager->getRepository(Menuvend::class)->findAll();

        return $this->render('admin/gerer_special.html.twig', [
            'journespec' => $journespec,
        ]);
    }
    #[Route('admin/gerer_sandwitch', name: 'app_gerersandwitch')]
    public function gerersandwtch(EntityManagerInterface $entityManager): Response
    {
        $sandwich = $entityManager->getRepository(Sandwitch::class)->findAll();
        return $this->render('admin/gerer_sandwitch.html.twig', [
            'sandwichs'=>$sandwich,
        ]);
    }
    #[Route('admin/gerer_tendance', name: 'app_gerertendance')]
    public function gerertendane(EntityManagerInterface $entityManager): Response
    {
        $menutendance = $entityManager->getRepository(Tendance::class)->findAll();

        return $this->render('admin/gerer_tendance.html.twig', [
            'tendance' => $menutendance,
        ]);
    }
    #[Route('admin/gerer_texte', name: 'app_gerertexte')]
    public function gerertexte(EntityManagerInterface $entityManager): Response
    {
        $gerertexte = $entityManager->getRepository(Texte::class)->findBy([], ['id' => 'DESC'], 1);
        return $this->render('admin/gerer_texte.html.twig', [
            'gerertexte' => $gerertexte,
        ]);
    }
    #[Route('admin/ajouter_texte', name: 'app_textes')]
    public function ajoutertexte(Request $request,EntityManagerInterface $entityManager): Response
    {
        if ($request->isMethod('POST')) {
            // Récupérer les données du formulaire
            $texte = $request->request->get('description');
           // Gestion du fichier

            // Valider les données du formulaire
            if ($texte) {
                // Créer un nouvel objet Boisson
                $textes = new Texte();
                $textes->setTexte($texte);
                

                // Gérer le téléchargement de l'image (si présente)
               

                // Sauvegarder l'entité dans la base de données
                $entityManager->persist($textes);
                $entityManager->flush();

                // Ajouter un message flash pour confirmation
                $this->addFlash('success', 'La boisson a été ajoutée avec succès !');

                // Redirection vers une autre page (par exemple la liste des boissons)
                return $this->redirectToRoute('app_gerertexte');
            } else {
                // En cas d'erreur, ajouter un message flash
                $this->addFlash('error', 'Tous les champs sont obligatoires.');
            }
        }
        return $this->render('admin/ajouter_texte.html.twig', [
            'controller_name' => 'AdminController',
        ]);
    }

    #[Route('/profil/{id}', name: 'app_profil')]
    public function profil(int $id, Request $request,EntityManagerInterface $entityManager,UserPasswordHasherInterface $passwordHasher): Response
    {

        $chiken = $entityManager->getRepository(Chiken::class)->findAll();
        $sandwich = $entityManager->getRepository(Sandwitch::class)->findAll();
        $boisson = $entityManager->getRepository(Boisson::class)->findAll();
        $tendance = $entityManager->getRepository(tendance::class)->findAll();
        $special = $entityManager->getRepository(Menuvend::class)->findAll();

        $user = $entityManager->getRepository(User::class)->find($id);

        if (!$user) {
            throw $this->createNotFoundException('La user demandée n\'existe pas.');
        }        
        if ($request->isMethod('POST')) {
            $ids = $request->request->get('id');
            $username = $request->request->get('nom');
            $email = $request->request->get('email');
            $telephone = $request->request->get('telephone');
            $image = $request->files->get('image');
            $mdp = $request->request->get('new-password');
            $confMdp = $request->request->get('confirm-password');
            if ($mdp && $confMdp) {
                
            
            if ($mdp !== $confMdp) {
                $this->addFlash('error', 'Les mots de passe ne correspondent pas.');
                return $this->redirectToRoute('app_profil',['id' => $ids]);
            }
            $passwordPattern = '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/';
            if (!preg_match($passwordPattern, $mdp)) {
                $this->addFlash('error', 'Le mot de passe doit contenir au moins 8 caractères, incluant au moins une lettre majuscule, une lettre minuscule, un chiffre, et un caractère spécial.');
                return $this->redirectToRoute('app_profil',['id' => $ids]);

            }
        }
            // Mise à jour des données de la medicament
            $user->setUsername($username);
            $user->setEmail($email);
            $user->setTelephone($telephone);
            $hashedPassword = $passwordHasher->hashPassword($user, $mdp);
            $user->setPassword($hashedPassword);
            if ($image) {
                $imageName = uniqid().'.'.$image->guessExtension();
                $image->move($this->getParameter('images_directory'), $imageName);
                $user->setImage($imageName);
                
            }

            // Mise à jour du mot de passe si fourni
           

            // Sauvegarde les modifications dans la base de données
            $entityManager->flush();
            $this->addFlash('success', 'vos modification ont ete effectue ');

            return $this->redirectToRoute('app_profil',['id' => $ids]);
        }

       
    
        // Validation des mots de passe (vérifier qu'ils sont identiques)
      
    
        // Validation du mot de passe
    

        return $this->render('admin/profile.html.twig', [
            'chiken' => $chiken,
            'sandwich' => $sandwich,
            'boisson' => $boisson,
            'tendance' => $tendance,
            'special' => $special,
        ]);
    }
}
