<?php
session_start();
require_once 'includes/session.php';
Verifier_session();
require_once 'Commande.class.php';

$panier = $_SESSION['panier'] ?? [];
if (empty($panier)) {
    header('Location: boutique.php');
    exit;
}

$total = 0;
foreach ($panier as $item) {
    $total += $item['prix'] * $item['quantite'];
}

$erreur  = '';
$succes  = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $adresse = trim($_POST['adresse_livraison'] ?? '');

    if (empty($adresse)) {
        $erreur = "Veuillez saisir une adresse de livraison.";
    } else {
        try {
            $c = new Commande();
            $id_commande = $c->creerCommande(
                intval($_SESSION['id_utilisateur']),
                $adresse,
                $total,
                $panier
            );

            require_once 'connexion.php';
            $cnx = new connexion();
            $pdo = $cnx->CNXbase();
            $pdo->prepare("DELETE FROM panier WHERE id_utilisateur = :u")
                ->execute([':u' => $_SESSION['id_utilisateur']]);
            $_SESSION['panier'] = [];

            $succes = true;

        } catch (Exception $e) {
            $erreur = "Erreur lors de la commande : " . $e->getMessage();
        }
    }
}

require_once 'commandeForm.php';