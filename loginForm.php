<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Connexion - Ma Boutique</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-5 col-lg-4">
            <div class="card shadow-sm border-0">
                <div class="card-body p-5">
                    <h2 class="text-center fw-bold text-uppercase mb-4">Connexion</h2>

                    <?php if ($message): ?>
                        <div class="alert alert-warning"><?= $message ?></div>
                    <?php endif; ?>
                    <?php if ($erreur): ?>
                        <div class="alert alert-danger"><?= $erreur ?></div>
                    <?php endif; ?>

                    <form method="POST" action="login.php">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Adresse e-mail</label>
                            <input type="email" name="email" class="form-control"
                                   value="<?= htmlspecialchars($_POST['email'] ?? '') ?>"
                                   placeholder="votre@email.com" required autofocus>
                        </div>
                        <div class="mb-4">
                            <label class="form-label fw-bold">Mot de passe</label>
                            <input type="password" name="mot_de_passe" class="form-control" placeholder="••••••••" required>
                        </div>
                        <button type="submit" name="login" class="btn btn-dark w-100 text-uppercase py-2">Se connecter</button>
                    </form>

                    <hr class="my-4">
                    <p class="text-center mb-0">Pas de compte ? <a href="inscription.php" class="fw-bold">S'inscrire</a></p>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
