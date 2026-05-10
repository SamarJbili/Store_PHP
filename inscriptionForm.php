<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Inscription - Ma Boutique</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow-sm border-0">
                <div class="card-body p-5">
                    <h2 class="text-center fw-bold text-uppercase mb-4">Créer un compte</h2>

                    <?php if ($succes): ?>
                        <div class="alert alert-success"><?= $succes ?> <a href="login.php">→ Se connecter</a></div>
                    <?php endif; ?>
                    <?php if ($erreur): ?>
                        <div class="alert alert-danger"><?= $erreur ?></div>
                    <?php endif; ?>

                    <form method="POST" action="inscription.php">
                        <table class="table table-striped">
                            <tr>
                                <td><label>Nom :</label></td>
                                <td><input type="text" name="nom" class="form-control" value="<?= htmlspecialchars($_POST['nom'] ?? '') ?>" required></td>
                            </tr>
                            <tr>
                                <td><label>Prénom :</label></td>
                                <td><input type="text" name="prenom" class="form-control" value="<?= htmlspecialchars($_POST['prenom'] ?? '') ?>" required></td>
                            </tr>
                            <tr>
                                <td><label>Email :</label></td>
                                <td><input type="email" name="email" class="form-control" value="<?= htmlspecialchars($_POST['email'] ?? '') ?>" required></td>
                            </tr>
                            <tr>
                                <td><label>Téléphone :</label></td>
                                <td><input type="tel" name="telephone" class="form-control" value="<?= htmlspecialchars($_POST['telephone'] ?? '') ?>"></td>
                            </tr>
                            <tr>
                                <td><label>Adresse :</label></td>
                                <td><textarea name="adresse" class="form-control"><?= htmlspecialchars($_POST['adresse'] ?? '') ?></textarea></td>
                            </tr>
                            <tr>
                                <td><label>Mot de passe :</label></td>
                                <td><input type="password" name="mot_de_passe" class="form-control" minlength="6" required></td>
                            </tr>
                            <tr>
                                <td><label>Confirmer :</label></td>
                                <td><input type="password" name="confirmation" class="form-control" required></td>
                            </tr>
                            <tr>
                                <td colspan="2">
                                    <input type="submit" name="envoyer" value="S'inscrire" class="btn btn-dark w-100 text-uppercase">
                                </td>
                            </tr>
                        </table>
                    </form>

                    <p class="text-center">Déjà un compte ? <a href="login.php" class="fw-bold">Se connecter</a></p>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
