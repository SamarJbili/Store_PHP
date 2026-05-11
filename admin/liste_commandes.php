<?php
session_start();
require_once '../includes/session.php';
Verifier_admin();
require_once '../Commande.class.php';

$c = new Commande();


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['changer_statut'])) {
    $c->changerStatut(intval($_POST['id_commande']), $_POST['nouveau_statut']);
    header('Location: liste_commandes.php'); exit;
}

$statut  = $_GET['statut'] ?? '';
$search  = trim($_GET['search'] ?? '');
$commandes = $c->listeCommandes($statut, $search);
$stats     = $c->statsParStatut();

$statutColors = [
    'en attente' => 'warning',
    'confirmée'  => 'info',
    'expédiée'   => 'primary',
    'livrée'     => 'success',
    'annulée'    => 'danger',
];
$tousStatuts = ['en attente','confirmée','expédiée','livrée','annulée'];
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Liste des commandes - Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container-fluid py-4">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-0"> Gestion des commandes</h2>
            <small class="text-muted"><?= count($commandes) ?> commande(s)</small>
        </div>
        <a href="tableau_de_bord.php" class="btn btn-outline-secondary btn-sm"><i class="bi bi-arrow-left"></i> Dashboard</a>
    </div>

    
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <form method="GET" class="row g-2 align-items-end">
                <div class="col-md-4">
                    <label class="form-label fw-semibold small text-uppercase">Recherche</label>
                    <input type="text" name="search" class="form-control form-control-sm"
                           placeholder="Nom, email ou n° commande..." value="<?= htmlspecialchars($search) ?>">
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-semibold small text-uppercase">Statut</label>
                    <select name="statut" class="form-select form-select-sm">
                        <option value="">Tous les statuts</option>
                        <?php foreach ($tousStatuts as $s): ?>
                            <option value="<?= $s ?>" <?= $statut === $s ? 'selected' : '' ?>><?= ucfirst($s) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-2 d-flex gap-2">
                    <button type="submit" class="btn btn-dark btn-sm w-100">Filtrer</button>
                    <a href="liste_commandes.php" class="btn btn-outline-secondary btn-sm">Reset</a>
                </div>
            </form>
        </div>
    </div>

  
    <div class="row g-3 mb-4">
        <?php foreach ($stats as $st): ?>
        <div class="col-md-2 col-sm-4">
            <div class="card border-0 shadow-sm text-center py-3">
                <div class="fw-bold fs-4"><?= $st['nb'] ?></div>
                <span class="badge bg-<?= $statutColors[$st['statut']] ?? 'secondary' ?> mt-1"><?= ucfirst($st['statut']) ?></span>
                <small class="text-muted mt-1"><?= number_format($st['ca'], 2, ',', ' ') ?> €</small>
            </div>
        </div>
        <?php endforeach; ?>
    </div>

  
    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <?php if (empty($commandes)): ?>
                <div class="text-center py-5"><div style="font-size:3rem;">📭</div><h5 class="mt-3 text-muted">Aucune commande</h5></div>
            <?php else: ?>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-dark">
                        <tr>
                            <th>#</th><th>Client</th><th>Date</th><th>Adresse</th>
                            <th class="text-center">Total</th><th class="text-center">Statut</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($commandes as $cmd): ?>
                        <tr>
                            <td><strong>#<?= $cmd['id_commande'] ?></strong></td>
                            <td>
                                <div class="fw-semibold"><?= htmlspecialchars($cmd['nom']) ?></div>
                                <small class="text-muted"><?= htmlspecialchars($cmd['email']) ?></small>
                            </td>
                            <td>
                                <div><?= date('d/m/Y', strtotime($cmd['date_commande'])) ?></div>
                                <small class="text-muted"><?= date('H:i', strtotime($cmd['date_commande'])) ?></small>
                            </td>
                            <td><small class="text-muted" style="max-width:150px;display:block;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;"><?= htmlspecialchars($cmd['adresse_livraison']) ?></small></td>
                            <td class="text-center fw-bold"><?= number_format($cmd['total'], 2, ',', ' ') ?> €</td>
                            <td class="text-center">
                                <span class="badge bg-<?= $statutColors[$cmd['statut']] ?? 'secondary' ?>"><?= ucfirst($cmd['statut']) ?></span>
                            </td>
                            <td class="text-center">
                                <div class="d-flex gap-1 justify-content-center">
                                    <a href="detail_commande.php?id=<?= $cmd['id_commande'] ?>" class="btn btn-sm btn-outline-primary" title="Voir"><i class="bi bi-eye"></i></a>
                                    <form method="POST" class="d-flex gap-1">
                                        <input type="hidden" name="id_commande" value="<?= $cmd['id_commande'] ?>">
                                        <select name="nouveau_statut" class="form-select form-select-sm" style="width:130px;">
                                            <?php foreach ($tousStatuts as $s): ?>
                                                <option value="<?= $s ?>" <?= $cmd['statut'] === $s ? 'selected' : '' ?>><?= ucfirst($s) ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                        <button type="submit" name="changer_statut" class="btn btn-sm btn-dark"><i class="bi bi-check-lg"></i></button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>