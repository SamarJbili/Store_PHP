<?php
session_start();
require_once 'includes/session.php';
Verifier_session();
require_once 'Utilisateur.class.php';

$id = $_SESSION['id_utilisateur'];
$us = new Utilisateur();

$res         = $us->getUtilisateur($id);
$data        = $res->fetchAll(PDO::FETCH_ASSOC);
$utilisateur = $data[0];

$succes      = "";
$erreur      = "";
$succes_mdp  = "";
$erreur_mdp  = "";

if (isset($_POST['action_profil'])) {
    $us->nom       = $_POST['nom'];
    $us->prenom    = $_POST['prenom'];
    $us->email     = $_POST['email'];
    $us->telephone = $_POST['telephone'];
    $us->adresse   = $_POST['adresse'];
    $us->modifierUtilisateur($id);
    $_SESSION['nom']    = $us->nom;
    $_SESSION['prenom'] = $us->prenom;
    $succes = "Profil mis à jour avec succès.";
    
    $res         = $us->getUtilisateur($id);
    $data        = $res->fetchAll(PDO::FETCH_ASSOC);
    $utilisateur = $data[0];
}

if (isset($_POST['action_mdp'])) {
    $ancien = $_POST['ancien_mdp'];
    $nouveau = $_POST['nouveau_mdp'];
    $confirmation = $_POST['confirmation_mdp'];

    if (!password_verify($ancien, $utilisateur['mot_de_passe'])) {
        $erreur_mdp = "L'ancien mot de passe est incorrect.";
    } elseif ($nouveau !== $confirmation) {
        $erreur_mdp = "Les nouveaux mots de passe ne correspondent pas.";
    } elseif (strlen($nouveau) < 6) {
        $erreur_mdp = "Minimum 6 caractères.";
    } else {
        $us->mot_de_passe = $nouveau;
        $us->changerMotDePasse($id);
        $succes_mdp = "Mot de passe modifié avec succès.";
    }
}
require_once 'profilForm.php';