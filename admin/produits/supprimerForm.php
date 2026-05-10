<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Supprimer</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-4 text-center">
            
            <!-- Lien de retour simple -->
            <a href="../tableau_de_bord.php" class="text-decoration-none text-muted small">← Retour au tableau de bord</a>

            <div class="card shadow-sm border-0 mt-3 p-4">
                
                <!-- Image ou icône -->
                <div class="mb-3">
                    <?php if ($produit['image']): ?>
                        <img src="../../images/produits/<?= htmlspecialchars($produit['image']) ?>" class="rounded" style="width: 100px; height: 100px; object-fit: cover;">
                    <?php else: ?>
                        <span style="font-size: 3rem;">📦</span>
                    <?php endif; ?>
                </div>

                <h5 class="fw-bold"><?= htmlspecialchars($produit['nom']) ?></h5>
                <p class="text-muted small">ID: #<?= $id ?> | <?= number_format($produit['prix'], 2) ?> €</p>

                <p class="text-danger fw-bold my-3">Voulez-vous vraiment supprimer ce produit ?</p>

                <!-- Formulaire simplifié -->
                <form method="POST" action="supprimer.php?id=<?= $id ?>">
                    <div class="d-grid gap-2">
                        <button type="submit" name="confirmer" class="btn btn-danger">Oui, supprimer définitivement</button>
                        <button type="submit" name="annuler" class="btn btn-light">Annuler</button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</div>

</body>
</html>