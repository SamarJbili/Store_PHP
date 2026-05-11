
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