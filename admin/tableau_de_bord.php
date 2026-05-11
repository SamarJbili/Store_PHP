<?php
session_start();
require_once '../includes/session.php';
Verifier_admin();
require_once '../Produit.class.php';

$p   = new Produit();
$res         = $p->listeProduits();
$produits    = $res->fetchAll(PDO::FETCH_ASSOC);

$message = $_GET['message'] ?? '';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Administration</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        
        body { background: #ffffff; color: #000000; font-family: 'Inter', sans-serif; }
        .admin-wrapper { max-width: 1100px; margin: 40px auto; }
        
        .top-nav { border-bottom: 1px solid #000; padding-bottom: 20px; margin-bottom: 30px; }
        
        .table { border: 1px solid #000; }
        .table thead { background: #f8f8f8; border-bottom: 2px solid #000; }
        .table th { font-weight: bold; text-transform: uppercase; font-size: 0.75rem; letter-spacing: 1px; color: #000; }
        
        .btn-black { background: #000; color: #fff; border-radius: 0; padding: 8px 20px; font-size: 0.85rem; border: 1px solid #000; }
        .btn-black:hover { background: #fff; color: #000; }
        
        .btn-outline-dark { border-radius: 0; font-size: 0.75rem; border: 1px solid #000; color: #000; }
        .btn-outline-dark:hover { background: #000; color: #fff; }
        
        .status { font-size: 0.8rem; font-weight: bold; color: #000; }
        
        img.prod-img { width: 50px; height: 50px; object-fit: cover; border: 1px solid #eee; }
        
        .text-muted { color: #666 !important; }
        .text-danger { color: #000 !important; text-decoration: underline; } /* Suppression du rouge sur "Supprimer" */
    </style>
</head>
<body>

<div class="container admin-wrapper">

    <div class="top-nav d-flex justify-content-between align-items-end">
        <div>
            <h1 class="h4 fw-bold mb-1 uppercase">Inventaire</h1>
            <p class="text-muted small mb-0">Utilisateur : <?= htmlspecialchars($_SESSION['prenom'] . ' ' . $_SESSION['nom']) ?></p>
        </div>
        <div>
            <a href="liste_commandes.php" class="btn btn-dark">
     Voir les commandes
</a>
            <a href="../index.php" class="text-dark small me-3 text-decoration-none">Boutique</a>
            <a href="../logout.php" class="text-dark small text-decoration-none fw-bold">Quitter</a>
        </div>
    </div>

    <?php if ($message): ?>
        <div class="border border-dark p-2 small mb-4 text-center fw-bold">
            <?= htmlspecialchars($message) ?>
        </div>
    <?php endif; ?>

    <div class="d-flex justify-content-between align-items-center mb-3">
        <span class="fw-bold small"><?= count($produits) ?> RÉFÉRENCES</span>
        <a href="produits/ajouter.php" class="btn btn-black">AJOUTER</a>
    </div>

    <div class="table-responsive">
        <table class="table align-middle">
            <thead>
                <tr>
                    <th class="ps-3">ID</th>
                    <th>Aperçu</th>
                    <th>Nom du produit</th>
                    <th>Catégorie</th>
                    <th>Prix HT</th>
                    <th>Stock</th>
                    <th class="text-end pe-3">Actions</th>
                </tr>
            </thead>
            <tbody>
            <?php if (count($produits) > 0): ?>
                <?php foreach ($produits as $row): ?>
                    <tr>
                        <td class="ps-3 text-muted small">#<?= $row['id_produit'] ?></td>
                        <td>
                            <?php if ($row['image']): ?>
                                <img src="../images/produits/<?= htmlspecialchars($row['image']) ?>" class="prod-img">
                            <?php else: ?>
                                <div class="bg-light border" style="width:50px;height:50px;"></div>
                            <?php endif; ?>
                        </td>
                        <td class="fw-bold small"><?= htmlspecialchars($row['nom']) ?></td>
                        <td><span class="text-muted small"><?= htmlspecialchars($row['categorie'] ?? '-') ?></span></td>
                        <td class="small"><?= number_format($row['prix'], 2, ',', ' ') ?> €</td>
                        <td class="small">
                            <?php if ($row['stock'] == 0): ?>
                                <span class="status">ÉPUISÉ</span>
                            <?php else: ?>
                                <span><?= $row['stock'] ?> UNITÉS</span>
                            <?php endif; ?>
                        </td>
                        <td class="text-end pe-3">
                            <a href="produits/modifier.php?id=<?= $row['id_produit'] ?>" class="btn btn-outline-dark btn-sm">Modifier</a>
                            <a href="produits/supprimer.php?id=<?= $row['id_produit'] ?>" 
                               class="btn-link btn-sm text-danger small ms-2"
                               onclick="return confirm('Confirmer ?')">Supprimer</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr><td colspan="7" class="text-center py-5 small">AUCUN PRODUIT TROUVÉ</td></tr>
            <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>