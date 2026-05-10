<?php
session_start();
require_once '../includes/session.php';
Verifier_admin();
require_once '../Commande.class.php';

$id = intval($_GET['id'] ?? 0);
if ($id <= 0) { header('Location: liste_commandes.php'); exit; }

$c   = new Commande();
$cmd = $c->getCommande($id);
if (!$cmd) { header('Location: liste_commandes.php'); exit; }

$lignes = $c->getLignes($id);

$statutColors = [
    'en attente' => 'warning',
    'confirmée'  => 'info',
    'expédiée'   => 'primary',
    'livrée'     => 'success',
    'annulée'    => 'danger',
];
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Détail commande #<?= $id ?> - Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container py-4" style="max-width:900px;">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold mb-0">Commande <span class="text-primary">#<?= $id ?></span></h2>
        <a href="liste_commandes.php" class="btn btn-outline-secondary btn-sm"><i class="bi bi-arrow-left"></i> Retour</a>
    </div>

    <div class="row g-4">
        <div class="col-md-5">
            <div class="card border-0 shadow-sm p-4 h-100">
                <h6 class="fw-bold text-uppercase mb-3"> Client</h6>
                <p class="mb-1"><strong><?= htmlspecialchars($cmd['nom']) ?></strong></p>
                <p class="text-muted mb-3"><?= htmlspecialchars($cmd['email']) ?></p>
                <h6 class="fw-bold text-uppercase mb-2"> Adresse de livraison</h6>
                <p class="text-muted"><?= nl2br(htmlspecialchars($cmd['adresse_livraison'])) ?></p>
                <h6 class="fw-bold text-uppercase mb-2"> Date</h6>
                <p class="text-muted"><?= date('d/m/Y à H:i', strtotime($cmd['date_commande'])) ?></p>
                <h6 class="fw-bold text-uppercase mb-2">Statut</h6>
                <span class="badge bg-<?= $statutColors[$cmd['statut']] ?? 'secondary' ?> fs-6"><?= ucfirst($cmd['statut']) ?></span>
            </div>
        </div>

        <div class="col-md-7">
            <div class="card border-0 shadow-sm p-4">
                <h6 class="fw-bold text-uppercase mb-3">🛒 Articles commandés</h6>
                <?php foreach ($lignes as $ligne): ?>
                <div class="d-flex align-items-center gap-3 mb-3 pb-3 border-bottom">
                    <?php if ($ligne['image']): ?>
                        <img src="../images/produits/<?= htmlspecialchars($ligne['image']) ?>"
                             style="width:60px;height:60px;object-fit:cover;border-radius:8px;">
                    <?php else: ?>
                        <div class="bg-light d-flex align-items-center justify-content-center"
                             style="width:60px;height:60px;border-radius:8px;font-size:1.5rem;">📦</div>
                    <?php endif; ?>
                    <div class="flex-grow-1">
                        <div class="fw-semibold"><?= htmlspecialchars($ligne['nom']) ?></div>
                        <small class="text-muted"><?= number_format($ligne['prix_unitaire'], 2, ',', ' ') ?> € × <?= $ligne['quantite'] ?></small>
                    </div>
                    <div class="fw-bold"><?= number_format($ligne['prix_unitaire'] * $ligne['quantite'], 2, ',', ' ') ?> €</div>
                </div>
                <?php endforeach; ?>
                <div class="d-flex justify-content-between fw-bold fs-5 mt-2">
                    <span>Total</span>
                    <span><?= number_format($cmd['total'], 2, ',', ' ') ?> €</span>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>