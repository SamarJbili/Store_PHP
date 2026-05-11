<?php
session_start();
require_once '../../includes/session.php';
Verifier_admin();
require_once '../../Produit.class.php';

$id = intval($_GET['id'] ?? 0);
if ($id <= 0) { header("location:../tableau_de_bord.php"); exit(); }

$p   = new Produit();
$res = $p->getProduit($id);
$data    = $res->fetchAll(PDO::FETCH_ASSOC);
if (!$data) { header("location:../tableau_de_bord.php"); exit(); }
$produit = $data[0];

if (isset($_POST['confirmer'])) {
    if ($produit['image']) {
        $chemin = '../../images/produits/' . $produit['image'];
        if (file_exists($chemin)) unlink($chemin);
    }
    $p->supprimerProduit($id);
    header('location:../tableau_de_bord.php?message=Produit+supprimé+avec+succès&type=success');
    exit();
}

if (isset($_POST['annuler'])) {
    header("location:../tableau_de_bord.php");
    exit();
}

require_once 'supprimerForm.php';
