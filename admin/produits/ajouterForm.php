<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Ajouter un produit - Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container py-4">
    <h4 class="fw-bold mb-4"> Ajouter un produit <small><a href="../tableau_de_bord.php" class="text-muted fs-6">← Retour</a></small></h4>

    <?php if ($erreur): ?>
        <div class="alert alert-danger"><?= $erreur ?></div>
    <?php endif; ?>

    <div class="card shadow-sm border-0">
        <div class="card-body p-4">
            <form method="POST" action="ajouter.php" enctype="multipart/form-data">
                <table class="table table-striped">
                    <tr>
                        <td><label>Nom :</label></td>
                        <td><input type="text" name="nom" class="form-control" value="<?= htmlspecialchars($_POST['nom'] ?? '') ?>" required></td>
                    </tr>
                    <tr>
                        <td><label>Description :</label></td>
                        <td><textarea name="description" class="form-control"><?= htmlspecialchars($_POST['description'] ?? '') ?></textarea></td>
                    </tr>
                    <tr>
                        <td><label>Prix (€) :</label></td>
                        <td><input type="number" name="prix" step="0.01" min="0" class="form-control" value="<?= htmlspecialchars($_POST['prix'] ?? '') ?>" required></td>
                    </tr>
                    <tr>
                        <td><label>Stock :</label></td>
                        <td><input type="number" name="stock" min="0" class="form-control" value="<?= htmlspecialchars($_POST['stock'] ?? '0') ?>" required></td>
                    </tr>
                    <tr>
                        <td><label>Catégorie :</label></td>
                        <td>
                            <select name="id_categorie" class="form-select">
                                <option value="0">-- Sans catégorie --</option>
                                <?php foreach ($categories as $cat) { ?>
                                    <option value="<?= $cat['id_categorie'] ?>"><?= htmlspecialchars($cat['nom']) ?></option>
                                <?php } ?>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td><label>Image :</label></td>
                        <td><input type="file" name="image" accept=".jpg,.jpeg,.png,.webp">
                            <small class="text-muted">JPG, PNG, WEBP. Max 2 Mo.</small></td>
                    </tr>
                    <tr>
                        <td colspan="2" class="d-flex gap-2">
                            <input type="submit" name="envoyer" value="Enregistrer" class="btn btn-dark text-uppercase">
                            <a href="../tableau_de_bord.php" class="btn btn-outline-secondary text-uppercase">Annuler</a>
                        </td>
                    </tr>
                </table>
            </form>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
