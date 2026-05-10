<?php
session_start();
require_once 'includes/session.php';
Verifier_session();
require_once 'Commande.class.php';

/* Rediriger si panier vide */
$panier = $_SESSION['panier'] ?? [];
if (empty($panier)) {
    header('Location: boutique.php');
    exit;
}

$total = 0;
foreach ($panier as $item) {
    $total += $item['prix'] * $item['quantite'];
}

$erreur  = '';
$succes  = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $adresse = trim($_POST['adresse_livraison'] ?? '');

    if (empty($adresse)) {
        $erreur = "Veuillez saisir une adresse de livraison.";
    } else {
        try {
            $c = new Commande();
            $id_commande = $c->creerCommande(
                intval($_SESSION['id_utilisateur']),
                $adresse,
                $total,
                $panier
            );

            // Vider le panier session + BDD
            require_once 'connexion.php';
            $cnx = new connexion();
            $pdo = $cnx->CNXbase();
            $pdo->prepare("DELETE FROM panier WHERE id_utilisateur = :u")
                ->execute([':u' => $_SESSION['id_utilisateur']]);
            $_SESSION['panier'] = [];

            $succes = true;

        } catch (Exception $e) {
            $erreur = "Erreur lors de la commande : " . $e->getMessage();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Passer la commande - Ma Boutique</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>
<body class="bg-light">
<?php include 'includes/navbar.php'; ?>

<div class="container py-5" style="max-width:800px;">
    <h2 class="fw-bold mb-4"> Passer la commande</h2>

    <?php if ($succes): ?>
        <div class="card shadow-sm border-0 text-center p-5">
            <div style="font-size:4rem;"></div>
            <h3 class="fw-bold mt-3">Commande confirmée !</h3>
            <p class="text-muted mt-2">Merci pour votre achat. Votre commande a bien été enregistrée.</p>
            <a href="boutique.php" class="btn btn-dark text-uppercase mt-3">Retour à la boutique</a>
        </div>

    <?php else: ?>

        <?php if ($erreur): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($erreur) ?></div>
        <?php endif; ?>

        <div class="row g-4">
            <div class="col-lg-7">
                <div class="card shadow-sm border-0 p-4">
                    <h5 class="fw-bold mb-4">Informations de livraison</h5>
                    <form method="POST" action="commande.php">
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Adresse de livraison <span class="text-danger">*</span></label>
                            <textarea name="adresse_livraison" class="form-control" rows="3"
                                      placeholder="Numéro, rue, ville, code postal..."
                                      required><?= htmlspecialchars($_POST['adresse_livraison'] ?? '') ?></textarea>
                        </div>
                        <hr class="my-4">
                        
                        <button type="submit" class="btn btn-dark w-100 text-uppercase mt-2 py-2 fs-6">
                             Confirmer la commande
                        </button>
                    </form>
                </div>
            </div>

            <div class="col-lg-5">
                <div class="card shadow-sm border-0 p-4">
                    <h5 class="fw-bold mb-3">Récapitulatif</h5>
                    <?php foreach ($panier as $item): ?>
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span><?= htmlspecialchars($item['nom']) ?> <small class="text-muted">x<?= $item['quantite'] ?></small></span>
                            <span class="fw-semibold"><?= number_format($item['prix'] * $item['quantite'], 2, ',', ' ') ?> €</span>
                        </div>
                    <?php endforeach; ?>
                    <hr>
                    <div class="d-flex justify-content-between mb-1">
                        <span class="text-muted">Livraison</span>
                        <span class="text-success">Gratuite</span>
                    </div>
                    <div class="d-flex justify-content-between fw-bold fs-5 mt-2">
                        <span>Total</span>
                        <span><?= number_format($total, 2, ',', ' ') ?> €</span>
                    </div>
                </div>
                <a href="panier.php" class="btn btn-outline-secondary w-100 mt-3 text-uppercase">← Modifier le panier</a>
            </div>
        </div>
    <?php endif; ?>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>