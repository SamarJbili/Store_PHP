<?php
session_start();
require_once 'Utilisateur.class.php';

if (isset($_SESSION['id_utilisateur'])) { header("Location: index.php"); exit(); }

$erreur  = "";
$message = htmlspecialchars($_GET['message'] ?? '');

if (isset($_POST['login'])) {
    $email = $_POST["email"];
    $mdp   = $_POST["mot_de_passe"];

    $us   = new Utilisateur();
    $data = $us->verifierConnexion($email, $mdp);

    if ($data) {
        $_SESSION['id_utilisateur'] = $data['id_utilisateur'];
        $_SESSION['nom']            = $data['nom'];
        $_SESSION['prenom']         = $data['prenom'];
        $_SESSION['email']          = $data['email'];
        $_SESSION['role']           = $data['role'];

        if ($data['role'] === 'admin') {
            header("location:admin/tableau_de_bord.php");
        } else {
            header("location:index.php");
        }
        exit();
    } else {
        $erreur = "Email ou mot de passe incorrect.";
    }
}

/* Afficher le formulaire */
require_once 'loginForm.php';
