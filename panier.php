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
        $stmt = $pdo->prepare("SELECT * FROM produits WHERE id_produit = :id");
        $stmt->execute([':id' => $id_produit]);
        $produit = $stmt->fetch(PDO::FETCH_ASSOC);

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

            $stmt2 = $pdo->prepare("
                INSERT INTO panier (id_utilisateur, id_produit, quantite, date_ajout)
                VALUES (:id_user, :id_produit, 1, NOW())
                ON DUPLICATE KEY UPDATE
                    quantite   = LEAST(quantite + 1, :stock),
                    date_ajout = NOW()
            ");
            $stmt2->execute([
                ':id_user'    => $id_user,
                ':id_produit' => $id_produit,
                ':stock'      => (int)$produit['stock'],
            ]);
        }
    }

    header('Location: panier.php'); exit;
}


if (isset($_GET['supprimer'])) {
    $id = intval($_GET['supprimer']);

    $stmt = $pdo->prepare("DELETE FROM panier WHERE id_utilisateur = :u AND id_produit = :p");
    $stmt->execute([':u' => $id_user, ':p' => $id]);
    unset($_SESSION['panier'][$id]);

    header('Location: panier.php'); exit;
}


if (isset($_GET['vider'])) {
    $stmt = $pdo->prepare("DELETE FROM panier WHERE id_utilisateur = :u");
    $stmt->execute([':u' => $id_user]);
    $_SESSION['panier'] = [];
    header('Location: panier.php'); exit;
}


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_qty'])) {
    foreach ($_POST['quantite'] as $id => $qty) {
        $id  = intval($id);
        $qty = intval($qty);
        if ($qty <= 0) {
            $stmt = $pdo->prepare("DELETE FROM panier WHERE id_utilisateur = :u AND id_produit = :p");
            $stmt->execute([':u' => $id_user, ':p' => $id]);
            unset($_SESSION['panier'][$id]);
        } else {
            $stmt = $pdo->prepare("UPDATE panier SET quantite = :q WHERE id_utilisateur = :u AND id_produit = :p");
            $stmt->execute([':q' => $qty, ':u' => $id_user, ':p' => $id]);
            if (isset($_SESSION['panier'][$id])) $_SESSION['panier'][$id]['quantite'] = $qty;
        }
    }
    header('Location: panier.php'); exit;
}


$stmt = $pdo->prepare("
    SELECT p.id_produit, pr.nom, pr.prix, pr.image, p.quantite
    FROM panier p
    JOIN produits pr ON pr.id_produit = p.id_produit
    WHERE p.id_utilisateur = :u
");
$stmt->execute([':u' => $id_user]);
$lignes = $stmt->fetchAll(PDO::FETCH_ASSOC);

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