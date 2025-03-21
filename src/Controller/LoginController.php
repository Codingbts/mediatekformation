<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

/**
 * Contrôleur de gestion de l'authentification.
 *
 * Ce contrôleur gère la connexion et la déconnexion des utilisateurs.
 */
class LoginController extends AbstractController
{
    /**
     * Affiche la page de connexion.
     * @param AuthenticationUtils $authenticationUtils
     * @return Response
     */
    #[Route('/login', name: 'app_login')]
    public function index(AuthenticationUtils $authenticationUtils): Response
    {
        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();
        
        $errormsg="";
        
        if ($error != null) {
            $errormsg = 'Le login ou le mot de passe est incorrect, veuillez réessayer.';
        }
        
        
        return $this->render('login/index.html.twig', [
            'last_username' => $lastUsername,
            'error' => $errormsg,
        ]);
    }

    /**
     * Déconnecte l'utilisateur.
     *
     */
    #[Route('/logout', name:'logout')]
    public function logout()
    {
        
    }
}
