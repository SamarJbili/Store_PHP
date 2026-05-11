<?php
session_start();
require_once 'Utilisateur.class.php';

if (isset($_SESSION['id_utilisateur'])) { header("Location: index.php"); exit(); }

$erreur = "";
$succes = "";

if (isset($_POST['envoyer'])) {
    $us               = new Utilisateur();
    $us->nom          = $_POST['nom'];
    $us->prenom       = $_POST['prenom'];
    $us->email        = $_POST['email'];
    $us->mot_de_passe = $_POST['mot_de_passe'];
    $us->telephone    = $_POST['telephone'];
    $us->adresse      = $_POST['adresse'];

    if ($_POST['mot_de_passe'] !== $_POST['confirmation']) {
        $erreur = "Les mots de passe ne correspondent pas.";
    } elseif ($us->emailExiste($us->email)) {
        $erreur = "Cette adresse e-mail est déjà utilisée.";
    } else {
        $us->insertUtilisateur();
        $succes = "Compte créé avec succès !";
    }
}

require_once 'inscriptionForm.php';
