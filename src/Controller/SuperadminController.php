<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class SuperadminController extends AbstractController
{
    #[Route('/superadmin', name: 'app_superadmin')]
    public function index(): Response
    {
        return $this->render('superadmin/index.html.twig', [
            'controller_name' => 'SuperadminController',
        ]);
    }
    #[Route('/superadmigererad', name: 'app_gereradmins')]
    public function gereradmins(EntityManagerInterface $entityManager): Response
    {
        $admin = $entityManager->getRepository(User::class)->findAll();

        return $this->render('superadmin/gerer_admin.html.twig', [
            'user' => $admin,
        ]);
    }
    #[Route('/superadminajouterad', name: 'app_ajouteradmins')]
    public function ajouteradmin(Request $request, EntityManagerInterface $entityManager, UserPasswordHasherInterface $passwordHasher): Response
    {
        if ($request->isMethod('POST')) {
            // Récupération des données du formulaire
            $username = $request->request->get('nom');
            $tel = $request->request->get('telephone');
            $email = $request->request->get('email');
            $mdp = $request->request->get('mdp');
            $confMdp = $request->request->get('confmdp');
        
            // Validation des mots de passe (vérifier qu'ils sont identiques)
            if ($mdp !== $confMdp) {
                $this->addFlash('error', 'Les mots de passe ne correspondent pas.');
                return $this->redirectToRoute('app_ajouteradmins');
            }
        
            // Validation du mot de passe
            $passwordPattern = '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/';
            if (!preg_match($passwordPattern, $mdp)) {
                $this->addFlash('error', 'Le mot de passe doit contenir au moins 8 caractères, incluant au moins une lettre majuscule, une lettre minuscule, un chiffre, et un caractère spécial.');
                return $this->redirectToRoute('app_ajouteradmins');
            }
        
            // Création d'un nouvel utilisateur
            $user = new User();
            $user->setUsername($username);
            $user->setTelephone($tel);
            $user->setEmail($email);
        
            // Ajout du rôle par défaut
            $user->setRoles(['ROLE_USER']); // Ajout du rôle par défaut
        
            // Encodage du mot de passe avec le service UserPasswordHasherInterface
            $hashedPassword = $passwordHasher->hashPassword($user, $mdp);
            $user->setPassword($hashedPassword);
        
            // Sauvegarde dans la base de données
            $entityManager->persist($user);
            $entityManager->flush();
        
            // Ajout d'un message de confirmation
            $this->addFlash('success', 'Compte créé avec succès!');
        
            // Redirection vers une autre page, par exemple, la page d'accueil
            return $this->redirectToRoute('app_gereradmins');
        }
        
        

        
        return $this->render('superadmin/ajouter_admin.html.twig', [
            'controller_name' => 'SuperadminController',
        ]);
    }
    #[Route('/superadminmodif/{id}', name: 'app_modifieradmin')]
    public function modifadmin(Request $request, EntityManagerInterface $entityManager,UserPasswordHasherInterface $passwordHasher, $id): Response
    {
        $user = $entityManager->getRepository(User::class)->find($id);

        if ($request->isMethod('POST')) {
            // Récupération des données du formulaire
            $username = $request->request->get('nom');
            $tel = $request->request->get('telephone');
            $email = $request->request->get('email');
            $mdp = $request->request->get('mdp');
            $confMdp = $request->request->get('confmdp');
        
            // Validation des mots de passe (vérifier qu'ils sont identiques)
            if ($mdp !== $confMdp) {
                $this->addFlash('error', 'Les mots de passe ne correspondent pas.');
                return $this->redirectToRoute('app_ajouteradmins');
            }
        
            // Validation du mot de passe
            $passwordPattern = '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/';
            if (!preg_match($passwordPattern, $mdp)) {
                $this->addFlash('error', 'Le mot de passe doit contenir au moins 8 caractères, incluant au moins une lettre majuscule, une lettre minuscule, un chiffre, et un caractère spécial.');
                return $this->render('superadmin/modifier_admin.html.twig', [
                    'user' => $user,
                ]);            }
        
            // Création d'un nouvel utilisateur
            $user = new User();
            $user->setUsername($username);
            $user->setTelephone($tel);
            $user->setEmail($email);
        
            // Ajout du rôle par défaut
            $user->setRoles(['ROLE_USER']); // Ajout du rôle par défaut
        
            // Encodage du mot de passe avec le service UserPasswordHasherInterface
            $hashedPassword = $passwordHasher->hashPassword($user, $mdp);
            $user->setPassword($hashedPassword);
        
            // Sauvegarde dans la base de données
            $entityManager->persist($user);
            $entityManager->flush();
        
            // Ajout d'un message de confirmation
            $this->addFlash('success', 'Compte créé avec succès!');
        
            // Redirection vers une autre page, par exemple, la page d'accueil
            return $this->redirectToRoute('app_gereradmins');
        }

        // Pré-remplir le formulaire avec les données de l'utilisateur
       

        // Gérer la soumission du formulaire
    
        return $this->render('superadmin/modifier_admin.html.twig', [
            'user' => $user,
        ]);
    }

}
