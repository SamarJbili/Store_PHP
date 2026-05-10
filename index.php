<?php
session_start();
require_once 'connexion.php';
require_once 'Produit.class.php';

$p = new Produit();
$res = $p->listeProduits();
$tousLesProduits = $res->fetchAll(PDO::FETCH_ASSOC);

// On prend les 4 premiers pour l'affichage
$produitsAffiches = array_slice($tousLesProduits, 0, 4);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Ma Boutique Simple</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Icônes simples -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <style>
        .product-card img { height: 250px; object-fit: cover; }
        .hero { background: #f8f9fa; padding: 60px 0; margin-bottom: 30px; }
    </style>
</head>
<body>

<?php include 'includes/navbar.php'; ?>

<!-- 1. HERO SECTION -->
<header class="hero text-center">
    <div class="container">
        <h1 class="display-4">Nouvelle Collection <?php echo date('Y'); ?></h1>
        <p class="lead">Le style, tout simplement.</p>
        <a href="boutique.php" class="btn btn-dark">Voir la boutique</a>
    </div>
</header>

<main class="container">
    
    <!-- 2. FILTRE RAPIDE -->
    <section class="row mb-5 g-3">
        <div class="col-md-8">
            <form action="boutique.php" method="GET" class="d-flex gap-2">
                <input type="search" name="q" class="form-control" placeholder="Rechercher un vêtement...">
                <button class="btn btn-outline-dark">Chercher</button>
            </form>
        </div>
        <div class="col text-end">
            <a href="boutique.php?categorie=femmes" class="btn btn-light border">Femmes</a>
            <a href="boutique.php?categorie=hommes" class="btn btn-light border">Hommes</a>
        </div>
    </section>

    <!-- 3. AFFICHAGE DES PRODUITS -->
    <section>
        <h2 class="mb-4">Nos Nouveautés</h2>
        <div class="row row-cols-1 row-cols-md-4 g-4">
            <?php foreach ($produitsAffiches as $prod): ?>
            <div class="col">
                <div class="card h-100 product-card shadow-sm">
                    <?php 
                        $img = !empty($prod['image']) ? "images/produits/".$prod['image'] : "https://via.placeholder.com/300x400?text=Produit";
                    ?>
                    <img src="<?= $img ?>" class="card-img-top" alt="<?= htmlspecialchars($prod['nom']) ?>">
                    
                    <div class="card-body text-center">
                        <h5 class="card-title"><?= htmlspecialchars($prod['nom']) ?></h5>
                        <p class="fw-bold fs-5"><?= number_format($prod['prix'], 2, ',', ' ') ?> €</p>
                    </div>
                    
                    <div class="card-footer bg-transparent border-0 d-flex justify-content-between pb-3">
                        <a href="produit.php?id=<?= $prod['id_produit'] ?>" class="btn btn-sm btn-outline-secondary">Détails</a>
                        <a href="panier.php?ajouter=<?= $prod['id_produit'] ?>" class="btn btn-sm btn-dark">
                            <i class="bi bi-cart-plus"></i> Ajouter
                        </a>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </section>

</main>

<!-- 4. FOOTER SIMPLE -->
<footer class="bg-dark text-white mt-5 py-4">
    <div class="container text-center">
        <p class="mb-1 fw-bold">SESHOP</p>
        <small>© <?= date('Y') ?> - Tous droits réservés</small>
        <div class="mt-2">
            <a href="#" class="text-white me-3"><i class="bi bi-facebook"></i></a>
            <a href="#" class="text-white"><i class="bi bi-instagram"></i></a>
        </div>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>