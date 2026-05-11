
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Boutique - Ma Boutique</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>
<body class="bg-light">
<?php include 'includes/navbar.php'; ?>

<div class="container py-5">
    <div class="row g-4">

        <div class="col-lg-3">
            <div class="card p-4 shadow-sm border-0">
                <h6 class="fw-bold text-uppercase mb-4">Filtrer</h6>
                <form method="GET" action="boutique.php">
                    <div class="mb-3">
                        <label class="form-label fw-bold small text-uppercase">Recherche</label>
                        <input type="search" name="q" class="form-control" placeholder="Nom..." value="<?= htmlspecialchars($q) ?>">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold small text-uppercase">Catégorie</label>
                        <select name="categorie" class="form-select">
                            <option value="0">Toutes</option>
                            <?php foreach ($categories as $cat) { ?>
                                <option value="<?= $cat['id_categorie'] ?>" <?= $id_categorie == $cat['id_categorie'] ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($cat['nom']) ?>
                                </option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold small text-uppercase">Trier par</label>
                        <select name="tri" class="form-select">
                            <option value="">Par défaut</option>
                            <option value="prix_asc" <?= $tri === 'prix_asc' ? 'selected' : '' ?>>Prix croissant</option>
                            <option value="prix_desc" <?= $tri === 'prix_desc' ? 'selected' : '' ?>>Prix décroissant</option>
                        </select>
                    </div>
                    <input type="submit" value="Appliquer" class="btn btn-dark w-100 text-uppercase mb-2">
                    <a href="boutique.php" class="btn btn-outline-secondary w-100 text-uppercase">Réinitialiser</a>
                </form>
            </div>
        </div>

        <!-- Produits -->
        <div class="col-lg-9">
            <p class="text-muted mb-4"><strong><?= count($produits) ?></strong> produit(s) trouvé(s)</p>

            <?php if (count($produits) > 0) { ?>
                <div class="row g-4">
                    <?php foreach ($produits as $row) { ?>
                        <div class="col-sm-6 col-xl-4">
                            <div class="card h-100 shadow-sm border-0">
                                <?php if ($row['image']): ?>
                                    <img src="images/produits/<?= htmlspecialchars($row['image']) ?>"
                                         class="card-img-top" style="height:230px;object-fit:cover;">
                                <?php else: ?>
                                    <div class="bg-light d-flex align-items-center justify-content-center" style="height:230px;font-size:4rem;">👗</div>
                                <?php endif; ?>
                                <div class="card-body d-flex flex-column">
                                    <?php if ($row['categorie']): ?>
                                        <small class="text-muted text-uppercase"><?= htmlspecialchars($row['categorie']) ?></small>
                                    <?php endif; ?>
                                    <h5 class="fw-bold mt-1"><?= htmlspecialchars($row['nom']) ?></h5>
                                    <p class="text-muted small"><?= htmlspecialchars($row['description']) ?></p>
                                    <div class="mt-auto">
                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                            <span class="fw-bold fs-5"><?= number_format($row['prix'], 2, ',', ' ') ?> €</span>
                                            <?php if ($row['stock'] > 0): ?>
                                                <small class="text-success fw-bold">✓ En stock</small>
                                            <?php else: ?>
                                                <small class="text-danger fw-bold">Rupture</small>
                                            <?php endif; ?>
                                        </div>
                                        <?php if ($row['stock'] > 0): ?>
                                            <a href="panier.php?ajouter=<?= $row['id_produit'] ?>" class="btn btn-dark w-100 text-uppercase">
                                                Ajouter au panier
                                            </a>
                                        <?php else: ?>
                                            <button class="btn btn-secondary w-100 text-uppercase" disabled>Indisponible</button>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                </div>
            <?php } else { ?>
                <div class="text-center py-5">
                    <div style="font-size:4rem;">🔍</div>
                    <h4 class="mt-3">Aucun produit trouvé</h4>
                    <a href="boutique.php" class="btn btn-dark mt-2 text-uppercase">Voir tous les produits</a>
                </div>
            <?php } ?>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>