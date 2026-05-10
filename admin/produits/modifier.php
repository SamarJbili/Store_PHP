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

/* Catégories */
$resCat     = $p->listeCategories();
$categories = $resCat->fetchAll(PDO::FETCH_ASSOC);

$erreur = "";

if (isset($_POST['envoyer'])) {
    $p->nom          = $_POST['nom'];
    $p->description  = $_POST['description'];
    $p->prix         = $_POST['prix'];
    $p->stock        = $_POST['stock'];
    $p->id_categorie = $_POST['id_categorie'];
    $photo           = $_FILES['image']['name'];

    if ($photo == "") {
        /* Garder l'ancienne image */
        $p->modifierProduitSansImage($id);
        header('location:../tableau_de_bord.php?message=Produit+modifié+avec+succès&type=success');
        exit();
    } else {
        $ext  = strtolower(pathinfo($photo, PATHINFO_EXTENSION));
        $extensions = ['jpg','jpeg','png','webp'];
        if (!in_array($ext, $extensions)) {
            $erreur = "Format image invalide.";
        } elseif ($_FILES['image']['size'] > 2 * 1024 * 1024) {
            $erreur = "L'image ne doit pas dépasser 2 Mo.";
        } else {
            $p->image = uniqid('prod_') . '.' . $ext;
            $dossier  = '../../images/produits/';
            if (!is_dir($dossier)) mkdir($dossier, 0755, true);
            move_uploaded_file($_FILES['image']['tmp_name'], $dossier . $p->image);
            /* Supprimer l'ancienne image */
            if ($produit['image'] && file_exists($dossier . $produit['image'])) {
                unlink($dossier . $produit['image']);
            }
            $p->modifierProduit($id);
            header('location:../tableau_de_bord.php?message=Produit+modifié+avec+succès&type=success');
            exit();
        }
    }
}

/* Afficher le formulaire */
require_once 'modifierForm.php';
