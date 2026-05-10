<?php
session_start();
require_once 'includes/session.php';
Verifier_session();
require_once 'connexion.php';

$cnx = new connexion();
$pdo = $cnx->CNXbase();
$id_user = intval($_SESSION['id_utilisateur']);

/* -------------------------------------------------------
   AJOUTER via GET (lien depuis boutique.php)
------------------------------------------------------- */
if (isset($_GET['ajouter'])) {
    $id_produit = intval($_GET['ajouter']);

    if ($id_produit > 0) {
        // Récupérer le produit depuis la BDD
        $stmt = $pdo->prepare("SELECT * FROM produits WHERE id_produit = :id");
        $stmt->execute([':id' => $id_produit]);
        $produit = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($produit && $produit['stock'] > 0) {
            // Mettre à jour la session
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

            // Sauvegarder en BDD (INSERT ou UPDATE)
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

/* -------------------------------------------------------
   SUPPRIMER
------------------------------------------------------- */
if (isset($_GET['supprimer'])) {
    $id = intval($_GET['supprimer']);

    $stmt = $pdo->prepare("DELETE FROM panier WHERE id_utilisateur = :u AND id_produit = :p");
    $stmt->execute([':u' => $id_user, ':p' => $id]);
    unset($_SESSION['panier'][$id]);

    header('Location: panier.php'); exit;
}

/* -------------------------------------------------------
   VIDER
------------------------------------------------------- */
if (isset($_GET['vider'])) {
    $stmt = $pdo->prepare("DELETE FROM panier WHERE id_utilisateur = :u");
    $stmt->execute([':u' => $id_user]);
    $_SESSION['panier'] = [];
    header('Location: panier.php'); exit;
}

/* -------------------------------------------------------
   METTRE À JOUR LES QUANTITÉS
------------------------------------------------------- */
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

/* -------------------------------------------------------
   CHARGER LE PANIER DEPUIS LA BDD
------------------------------------------------------- */
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
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Votre Panier - ModernShop</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<?php include 'includes/navbar.php'; ?>

<div class="container py-5">
    <h1 class="fw-light mb-5 text-center">VOTRE PANIER</h1>

    <?php if (empty($panier)): ?>
        <div class="text-center bg-white p-5 shadow-sm rounded">
            <p class="fs-4 text-muted">Votre panier semble vide.</p>
            <a href="boutique.php" class="btn btn-dark px-5 py-2 mt-3">RETOUR À LA BOUTIQUE</a>
        </div>
    <?php else: ?>
        <div class="row g-5">
            <div class="col-lg-8">
                <form method="POST">
                    <input type="hidden" name="update_qty" value="1">
                    <div class="bg-white shadow-sm rounded overflow-hidden">
                        <?php foreach ($panier as $id => $item): ?>
                        <div class="row align-items-center g-0 p-4 border-bottom">
                            <div class="col-2">
                                <img src="images/produits/<?= htmlspecialchars($item['image']) ?>" class="img-fluid rounded" style="max-height:80px;">
                            </div>
                            <div class="col-4 ps-3">
                                <h6 class="mb-0 fw-bold"><?= htmlspecialchars($item['nom']) ?></h6>
                                <small class="text-muted"><?= number_format($item['prix'], 2) ?> €</small>
                            </div>
                            <div class="col-3">
                                <input type="number" name="quantite[<?= $id ?>]" value="<?= $item['quantite'] ?>" min="1" class="form-control form-control-sm mx-auto" style="width:70px;">
                            </div>
                            <div class="col-2 text-end fw-bold">
                                <?= number_format($item['prix'] * $item['quantite'], 2) ?> €
                            </div>
                            <div class="col-1 text-end">
                                <a href="panier.php?supprimer=<?= $id ?>" class="text-danger text-decoration-none">✕</a>
                            </div>
                        </div>
                        <?php endforeach; ?>
                        <div class="p-3 bg-light d-flex justify-content-between">
                            <button type="submit" class="btn btn-outline-dark btn-sm">METTRE À JOUR LE PANIER</button>
                            <a href="panier.php?vider=1" class="text-muted small align-self-center">Vider tout le panier</a>
                        </div>
                    </div>
                </form>
            </div>

            <div class="col-lg-4">
                <div class="card border-0 shadow-sm p-4">
                    <h5 class="fw-bold mb-4">RÉCAPITULATIF</h5>
                    <div class="d-flex justify-content-between mb-2">
                        <span>Sous-total</span>
                        <span><?= number_format($total, 2) ?> €</span>
                    </div>
                    <div class="d-flex justify-content-between mb-3 text-success">
                        <span>Livraison</span>
                        <span>Offerte</span>
                    </div>
                    <hr>
                    <div class="d-flex justify-content-between fs-4 fw-bold mb-4">
                        <span>TOTAL</span>
                        <span><?= number_format($total, 2) ?> €</span>
                    </div>
                    <a href="commande.php" class="btn btn-dark w-100 py-3 fw-bold">COMMANDER MAINTENANT</a>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>
</body>
</html>