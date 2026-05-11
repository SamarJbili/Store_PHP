<?php
session_start();
require_once 'includes/session.php';
Verifier_session();
require_once 'connexion.php';

$cnx = new connexion();
$pdo = $cnx->CNXbase();
$id_user = intval($_SESSION['id_utilisateur']);

if (isset($_GET['ajouter'])) {
    $id_produit = intval($_GET['ajouter']);

    if ($id_produit > 0) {
        $res = $pdo->query("SELECT * FROM produits WHERE id_produit = $id_produit");
        $produit = $res->fetch(PDO::FETCH_ASSOC);

        if ($produit && $produit['stock'] > 0) {
            if (!isset($_SESSION['panier'])) $_SESSION['panier'] = [];
            $current_qty = $_SESSION['panier'][$id_produit]['quantite'] ?? 0;
            $new_qty = min($current_qty + 1, (int)$produit['stock']);
            $_SESSION['panier'][$id_produit] = [
                'id_produit' => $id_produit,
                'nom'        => $produit['nom'],
                'prix'       => $produit['prix'],
                'image'      => $produit['image'] ?? '',
                'quantite'   => $new_qty,
            ];

            $stock = (int)$produit['stock'];
            $existe = $pdo->query("SELECT quantite FROM panier WHERE id_utilisateur=$id_user AND id_produit=$id_produit");
            $data = $existe->fetch(PDO::FETCH_ASSOC);
            if ($data) {
                $q = min(((int)$data['quantite']) + 1, $stock);
                $pdo->exec("UPDATE panier SET quantite=$q, date_ajout=NOW() WHERE id_utilisateur=$id_user AND id_produit=$id_produit");
            } else {
                $pdo->exec("INSERT INTO panier (id_utilisateur, id_produit, quantite, date_ajout) VALUES ($id_user, $id_produit, 1, NOW())");
            }
        }
    }

    header('Location: panier.php'); exit;
}


if (isset($_GET['supprimer'])) {
    $id = intval($_GET['supprimer']);

    $pdo->exec("DELETE FROM panier WHERE id_utilisateur = $id_user AND id_produit = $id");
    unset($_SESSION['panier'][$id]);

    header('Location: panier.php'); exit;
}


if (isset($_GET['vider'])) {
    $pdo->exec("DELETE FROM panier WHERE id_utilisateur = $id_user");
    $_SESSION['panier'] = [];
    header('Location: panier.php'); exit;
}


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_qty'])) {
    foreach ($_POST['quantite'] as $id => $qty) {
        $id  = intval($id);
        $qty = intval($qty);
        if ($qty <= 0) {
            $pdo->exec("DELETE FROM panier WHERE id_utilisateur = $id_user AND id_produit = $id");
            unset($_SESSION['panier'][$id]);
        } else {
            $pdo->exec("UPDATE panier SET quantite = $qty WHERE id_utilisateur = $id_user AND id_produit = $id");
            if (isset($_SESSION['panier'][$id])) $_SESSION['panier'][$id]['quantite'] = $qty;
        }
    }
    header('Location: panier.php'); exit;
}


$res = $pdo->query("
    SELECT p.id_produit, pr.nom, pr.prix, pr.image, p.quantite
    FROM panier p
    JOIN produits pr ON pr.id_produit = p.id_produit
    WHERE p.id_utilisateur = $id_user
");
$lignes = $res->fetchAll(PDO::FETCH_ASSOC);

$_SESSION['panier'] = [];
foreach ($lignes as $ligne) {
    $id = intval($ligne['id_produit']);
    $_SESSION['panier'][$id] = [
        'id_produit' => $id,
        'nom'        => $ligne['nom'],
        'prix'       => $ligne['prix'],
        'image'      => $ligne['image'],
        'quantite'   => intval($ligne['quantite']),
    ];
}

$panier = $_SESSION['panier'];
$total  = 0;
foreach ($panier as $item) { $total += $item['prix'] * $item['quantite']; }

require_once 'panierForm.php';