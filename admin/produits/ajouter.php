<?php
session_start();
require_once '../../includes/session.php';
Verifier_admin();
require_once '../../Produit.class.php';

/* Récupérer les catégories */
$p   = new Produit();
$res = $p->listeCategories();
$categories = $res->fetchAll(PDO::FETCH_ASSOC);

$erreur = "";

if (isset($_POST['envoyer'])) {
    $p->nom          = $_POST['nom'];
    $p->description  = $_POST['description'];
    $p->prix         = $_POST['prix'];
    $p->stock        = $_POST['stock'];
    $p->id_categorie = $_POST['id_categorie'];
    $p->image        = '';

    /* Upload image */
    if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
        $extensions = ['jpg','jpeg','png','webp'];
        $ext        = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
        if (!in_array($ext, $extensions)) {
            $erreur = "Format image invalide.";
        } elseif ($_FILES['image']['size'] > 2 * 1024 * 1024) {
            $erreur = "L'image ne doit pas dépasser 2 Mo.";
        } else {
            $p->image    = uniqid('prod_') . '.' . $ext;
            $dossier     = '../../images/produits/';
            if (!is_dir($dossier)) mkdir($dossier, 0755, true);
            move_uploaded_file($_FILES['image']['tmp_name'], $dossier . $p->image);
        }
    }

    if (empty($erreur)) {
        $p->insertProduit();
        header('location:../tableau_de_bord.php?message=Produit+ajouté+avec+succès&type=success');
        exit();
    }
}

/* Afficher le formulaire */
require_once 'ajouterForm.php';
