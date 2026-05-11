<?php
session_start();
require_once 'includes/session.php';
Verifier_session();
require_once 'Produit.class.php';
require_once 'Panier.class.php';

$p   = new Produit();
$q   = trim($_GET['q'] ?? '');
$id_categorie = intval($_GET['categorie'] ?? 0);

if (!empty($q)) {
    $res = $p->rechercher($q);
} elseif ($id_categorie > 0) {
    $res = $p->getParCategorie($id_categorie);
} else {
    $res = $p->listeProduits();
}

$produits = $res->fetchAll(PDO::FETCH_ASSOC);


$tri = $_GET['tri'] ?? '';
if ($tri === 'prix_asc') {
    usort($produits, fn($a, $b) => $a['prix'] <=> $b['prix']);
} elseif ($tri === 'prix_desc') {
    usort($produits, fn($a, $b) => $b['prix'] <=> $a['prix']);
}

$resCat     = $p->listeCategories();
$categories = $resCat->fetchAll(PDO::FETCH_ASSOC);
require_once 'boutiqueForm.php';