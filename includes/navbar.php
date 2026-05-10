<?php
// includes/navbar.php

// Charger le panier depuis la BDD si connecté et pas encore chargé
if (isset($_SESSION['id_utilisateur'])) {
    require_once __DIR__ . '/../Panier.class.php';
    $navPanier = new Panier();
    $navPanier->chargerDepuisBDD($_SESSION['id_utilisateur']);
}

$total_articles = 0;
if (isset($_SESSION['panier'])) {
    foreach ($_SESSION['panier'] as $item) {
        $total_articles += $item['quantite'];
    }
}
?>
<nav class="navbar navbar-expand-lg navbar-light bg-white py-3 shadow-sm border-bottom">
  <div class="container">
    <a class="navbar-brand fw-light tracking-widest text-dark" href="index.php" style="letter-spacing: 2px;">
      <span class="fw-bold">SE</span>SHOP
    </a>

    <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navRes">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navRes">
      <ul class="navbar-nav mx-auto">
        <li class="nav-item"><a class="nav-link px-3 fw-semibold" href="index.php">Accueil</a></li>
        <li class="nav-item"><a class="nav-link px-3 fw-semibold" href="boutique.php">Boutique</a></li>
        <li class="nav-item"><a class="nav-link px-3 fw-semibold" href="contact.php">Contact</a></li>
      </ul>

      <div class="d-flex align-items-center gap-3">
        <a href="panier.php" class="btn position-relative border-0 p-2 text-dark">
          <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="currentColor" class="bi bi-bag" viewBox="0 0 16 16">
            <path d="M8 1a2.5 2.5 0 0 1 2.5 2.5V4h-5v-.5A2.5 2.5 0 0 1 8 1m3.5 3v-.5a3.5 3.5 0 1 0-7 0V4H1v10a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V4zM2 5h12v9a1 1 0 0 1-1 1H3a1 1 0 0 1-1-1z"/>
          </svg>
          <?php if ($total_articles > 0): ?>
            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="font-size: 0.7rem;">
              <?= $total_articles ?>
            </span>
          <?php endif; ?>
        </a>

        <?php if (isset($_SESSION['id_utilisateur'])): ?>
          <div class="dropdown">
            <button class="btn btn-dark dropdown-toggle btn-sm fw-bold px-3" type="button" data-bs-toggle="dropdown">
              <?= strtoupper(substr($_SESSION['role'] ?? 'U', 0, 1)) ?>
            </button>
            <ul class="dropdown-menu dropdown-menu-end border-0 shadow">
              <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
                <li><a class="dropdown-item fw-bold text-primary" href="admin/tableau_de_bord.php">Dashboard Admin</a></li>
                <li><hr class="dropdown-divider"></li>
              <?php endif; ?>
              <li><a class="dropdown-item" href="profil.php">Mon Profil</a></li>
              <li><a class="dropdown-item text-danger" href="logout.php">Déconnexion</a></li>
            </ul>
          </div>
        <?php else: ?>
          <a href="login.php" class="btn btn-outline-dark btn-sm px-4 fw-bold">Login</a>
        <?php endif; ?>
      </div>
    </div>
  </div>
</nav>