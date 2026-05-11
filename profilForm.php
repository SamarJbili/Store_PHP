
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Mon Profil - Ma Boutique</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<?php include 'includes/navbar.php'; ?>
<div class="container py-5">
    <h2 class="fw-bold text-uppercase mb-5">Mon Profil</h2>
    <div class="row g-4">

        <div class="col-lg-7">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-dark text-white fw-bold text-uppercase py-3">Mes informations</div>
                <div class="card-body p-4">
                    <?php if ($succes): ?><div class="alert alert-success"><?= $succes ?></div><?php endif; ?>
                    <?php if ($erreur): ?><div class="alert alert-danger"><?= $erreur ?></div><?php endif; ?>
                    <form method="POST" action="profil.php">
                        <input type="hidden" name="action_profil" value="1">
                        <table class="table table-striped">
                            <tr>
                                <td><label>Nom :</label></td>
                                <td><input type="text" name="nom" class="form-control" value="<?= htmlspecialchars($utilisateur['nom']) ?>" required></td>
                            </tr>
                            <tr>
                                <td><label>Prénom :</label></td>
                                <td><input type="text" name="prenom" class="form-control" value="<?= htmlspecialchars($utilisateur['prenom']) ?>" required></td>
                            </tr>
                            <tr>
                                <td><label>Email :</label></td>
                                <td><input type="email" name="email" class="form-control" value="<?= htmlspecialchars($utilisateur['email']) ?>" required></td>
                            </tr>
                            <tr>
                                <td><label>Téléphone :</label></td>
                                <td><input type="tel" name="telephone" class="form-control" value="<?= htmlspecialchars($utilisateur['telephone'] ?? '') ?>"></td>
                            </tr>
                            <tr>
                                <td><label>Adresse :</label></td>
                                <td><textarea name="adresse" class="form-control"><?= htmlspecialchars($utilisateur['adresse'] ?? '') ?></textarea></td>
                            </tr>
                            <tr>
                                <td colspan="2"><input type="submit" value="Enregistrer" class="btn btn-dark text-uppercase"></td>
                            </tr>
                        </table>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-5">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-dark text-white fw-bold text-uppercase py-3">Changer le mot de passe</div>
                <div class="card-body p-4">
                    <?php if ($succes_mdp): ?><div class="alert alert-success"><?= $succes_mdp ?></div><?php endif; ?>
                    <?php if ($erreur_mdp): ?><div class="alert alert-danger"><?= $erreur_mdp ?></div><?php endif; ?>
                    <form method="POST" action="profil.php">
                        <input type="hidden" name="action_mdp" value="1">
                        <table class="table table-striped">
                            <tr>
                                <td><label>Ancien mot de passe :</label></td>
                                <td><input type="password" name="ancien_mdp" class="form-control" required></td>
                            </tr>
                            <tr>
                                <td><label>Nouveau mot de passe :</label></td>
                                <td><input type="password" name="nouveau_mdp" class="form-control" minlength="6" required></td>
                            </tr>
                            <tr>
                                <td><label>Confirmer :</label></td>
                                <td><input type="password" name="confirmation_mdp" class="form-control" required></td>
                            </tr>
                            <tr>
                                <td colspan="2"><input type="submit" value="Changer" class="btn btn-dark w-100 text-uppercase"></td>
                            </tr>
                        </table>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
